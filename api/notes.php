<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

// Helper to handle single or multiple image uploads
function handleMultipleImageUploads() {
    $uploadedUrls = [];
    $uploadDir = __DIR__ . '/../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if $_FILES['images'] exists (multiple files)
    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $count = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $count; $i++) {
            $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
            if ($error === UPLOAD_ERR_OK) {
                $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
                $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($ext, $allowed)) {
                    $newName = 'img_' . uniqid() . '_' . $i . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $uploadedUrls[] = 'uploads/' . $newName;
                    }
                }
            }
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $newName = 'img_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
                $uploadedUrls[] = 'uploads/' . $newName;
            }
        }
    }

    return $uploadedUrls;
}

// Helper to delete physical files from server
function deletePhysicalFiles($urls) {
    if (empty($urls)) return;
    if (!is_array($urls)) {
        $urls = json_decode($urls, true) ?: explode(',', $urls);
    }
    foreach ($urls as $url) {
        $url = trim($url);
        if (!empty($url)) {
            $filePath = __DIR__ . '/../' . ltrim($url, '/');
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }
}

if ($method === 'GET') {
    $folderId = $_GET['folder_id'] ?? null;
    if ($folderId) {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? AND folder_id = ? ORDER BY is_pinned DESC, updated_at DESC");
        $stmt->execute([$userId, $folderId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY is_pinned DESC, updated_at DESC");
        $stmt->execute([$userId]);
    }
    $notes = $stmt->fetchAll();
    foreach ($notes as &$n) {
        $n['isPinned'] = (bool)$n['is_pinned'];
        $n['folderId'] = $n['folder_id'];
        $n['updatedAt'] = $n['updated_at'];
        // Parse image_url JSON array or comma separated
        $n['imageUrls'] = [];
        if (!empty($n['image_url'])) {
            $decoded = json_decode($n['image_url'], true);
            if (is_array($decoded)) {
                $n['imageUrls'] = $decoded;
            } else {
                $n['imageUrls'] = array_filter(array_map('trim', explode(',', $n['image_url'])));
            }
        }
        $n['imageUrl'] = !empty($n['imageUrls']) ? $n['imageUrls'][0] : null;
    }
    jsonResponse(['success' => true, 'data' => $notes]);
}

if ($method === 'POST') {
    $id = $_POST['id'] ?? null;
    $folderId = $_POST['folder_id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $isPinned = isset($_POST['is_pinned']) ? ($_POST['is_pinned'] === 'true' || $_POST['is_pinned'] === '1' ? 1 : 0) : 0;
    $removedUrlsRaw = $_POST['removed_image_urls'] ?? '';

    if (empty($title)) {
        $json = getRequestBody();
        $id = $json['id'] ?? $id;
        $folderId = $json['folder_id'] ?? $folderId;
        $title = trim($json['title'] ?? '');
        $content = trim($json['content'] ?? '');
        $isPinned = !empty($json['is_pinned']) ? 1 : 0;
    }

    if (empty($title) || empty($content)) {
        jsonResponse(['success' => false, 'message' => 'Judul dan isi catatan wajib diisi'], 400);
    }

    // Delete explicitly removed files from server storage
    if (!empty($removedUrlsRaw)) {
        $removedUrls = is_array($removedUrlsRaw) ? $removedUrlsRaw : json_decode($removedUrlsRaw, true);
        if ($removedUrls) {
            deletePhysicalFiles($removedUrls);
        }
    }

    // Existing URLs kept by client
    $retainedUrls = [];
    if (isset($_POST['retained_image_urls'])) {
        $retainedRaw = $_POST['retained_image_urls'];
        $retainedUrls = is_array($retainedRaw) ? $retainedRaw : (json_decode($retainedRaw, true) ?: array_filter(explode(',', $retainedRaw)));
    }

    // New uploaded files
    $newUploadedUrls = handleMultipleImageUploads();

    // Combine retained + new URLs
    $finalImageUrls = array_values(array_filter(array_merge($retainedUrls, $newUploadedUrls)));
    $imageUrlsJson = !empty($finalImageUrls) ? json_encode($finalImageUrls) : null;

    if ($id) {
        // UPDATE existing note
        $stmt = $pdo->prepare("UPDATE notes SET folder_id = ?, title = ?, content = ?, image_url = ?, is_pinned = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$folderId ?: null, $title, $content, $imageUrlsJson, $isPinned, $id, $userId]);

        jsonResponse([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui',
            'data' => [
                'id' => $id,
                'folderId' => $folderId,
                'title' => $title,
                'content' => $content,
                'imageUrls' => $finalImageUrls,
                'imageUrl' => !empty($finalImageUrls) ? $finalImageUrls[0] : null,
                'isPinned' => (bool)$isPinned,
                'updatedAt' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        // INSERT new note
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, folder_id, title, content, image_url, is_pinned) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $folderId ?: null, $title, $content, $imageUrlsJson, $isPinned]);
        $newId = $pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => 'Catatan berhasil disimpan',
            'data' => [
                'id' => $newId,
                'folderId' => $folderId,
                'title' => $title,
                'content' => $content,
                'imageUrls' => $finalImageUrls,
                'imageUrl' => !empty($finalImageUrls) ? $finalImageUrls[0] : null,
                'isPinned' => (bool)$isPinned,
                'updatedAt' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}

if ($method === 'PUT') {
    $data = getRequestBody();
    $id = $data['id'] ?? null;

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Catatan tidak valid'], 400);
    }

    if (isset($data['toggle_pin'])) {
        $stmt = $pdo->prepare("UPDATE notes SET is_pinned = IF(is_pinned = 1, 0, 1), updated_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
    }

    jsonResponse(['success' => true, 'message' => 'Status pin catatan diperbarui']);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Catatan wajib disertakan'], 400);
    }

    // Retrieve and delete physical files from server
    $stmtSelect = $pdo->prepare("SELECT image_url FROM notes WHERE id = ? AND user_id = ?");
    $stmtSelect->execute([$id, $userId]);
    $note = $stmtSelect->fetch();
    if ($note && !empty($note['image_url'])) {
        deletePhysicalFiles($note['image_url']);
    }

    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Catatan dan berkas foto terkait berhasil dihapus']);
}
