<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

function handleImageUpload() {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            return null;
        }

        $newFileName = 'img_' . uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return 'uploads/' . $newFileName;
        }
    }
    return null;
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
        $n['imageUrl'] = $n['image_url'];
    }
    jsonResponse(['success' => true, 'data' => $notes]);
}

if ($method === 'POST') {
    $id = $_POST['id'] ?? null;
    $folderId = $_POST['folder_id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $isPinned = isset($_POST['is_pinned']) ? ($_POST['is_pinned'] === 'true' || $_POST['is_pinned'] === '1' ? 1 : 0) : 0;

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

    $imageUrl = handleImageUpload();

    if ($id) {
        // UPDATE existing note
        if (!$imageUrl && isset($_POST['existing_image_url'])) {
            $imageUrl = $_POST['existing_image_url'];
        }
        if ($imageUrl !== null) {
            $stmt = $pdo->prepare("UPDATE notes SET folder_id = ?, title = ?, content = ?, image_url = ?, is_pinned = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
            $stmt->execute([$folderId ?: null, $title, $content, $imageUrl, $isPinned, $id, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE notes SET folder_id = ?, title = ?, content = ?, is_pinned = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
            $stmt->execute([$folderId ?: null, $title, $content, $isPinned, $id, $userId]);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui',
            'data' => [
                'id' => $id,
                'folderId' => $folderId,
                'title' => $title,
                'content' => $content,
                'imageUrl' => $imageUrl,
                'isPinned' => (bool)$isPinned,
                'updatedAt' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        // CREATE new note
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, folder_id, title, content, image_url, is_pinned) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $folderId ?: null, $title, $content, $imageUrl, $isPinned]);
        $newId = $pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => 'Catatan berhasil disimpan',
            'data' => [
                'id' => $newId,
                'folderId' => $folderId,
                'title' => $title,
                'content' => $content,
                'imageUrl' => $imageUrl,
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
        // Robust toggle pin SQL query
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

    $stmtSelect = $pdo->prepare("SELECT image_url FROM notes WHERE id = ? AND user_id = ?");
    $stmtSelect->execute([$id, $userId]);
    $note = $stmtSelect->fetch();
    if ($note && !empty($note['image_url'])) {
        $filePath = __DIR__ . '/../' . $note['image_url'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Catatan berhasil dihapus']);
}
