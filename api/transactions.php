<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

function handleTransactionImageUpload() {
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

        $newFileName = 'tx_img_' . uniqid() . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return 'uploads/' . $newFileName;
        }
    }
    return null;
}

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC");
    $stmt->execute([$userId]);
    $transactions = $stmt->fetchAll();
    foreach ($transactions as &$tx) {
        $tx['imageUrl'] = $tx['image_url'];
    }
    jsonResponse(['success' => true, 'data' => $transactions]);
}

if ($method === 'POST') {
    $type = $_POST['type'] ?? null;
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $category = $_POST['category'] ?? 'Makanan';
    $date = $_POST['date'] ?? date('Y-m-d');
    $removedImage = $_POST['remove_image'] ?? false;

    if ($type === null || $amount === null || $title === null) {
        $data = getRequestBody();
        $type = $data['type'] ?? 'expense';
        $amount = (float)($data['amount'] ?? 0);
        $title = trim($data['title'] ?? '');
        $category = $data['category'] ?? 'Makanan';
        $date = $data['date'] ?? date('Y-m-d');
    }

    if ($amount <= 0 || empty($title)) {
        jsonResponse(['success' => false, 'message' => 'Jumlah saldo dan keterangan transaksi wajib diisi'], 400);
    }

    $imageUrl = handleTransactionImageUpload();

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, title, category, date, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $type, $amount, $title, $category, $date, $imageUrl]);
    $id = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Transaksi berhasil dicatat',
        'data' => [
            'id' => $id,
            'type' => $type,
            'amount' => $amount,
            'title' => $title,
            'category' => $category,
            'date' => $date,
            'imageUrl' => $imageUrl
        ]
    ]);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Transaksi wajib disertakan'], 400);
    }

    // Delete associated receipt photo from server storage
    $stmtSelect = $pdo->prepare("SELECT image_url FROM transactions WHERE id = ? AND user_id = ?");
    $stmtSelect->execute([$id, $userId]);
    $tx = $stmtSelect->fetch();
    if ($tx && !empty($tx['image_url'])) {
        $filePath = __DIR__ . '/../' . ltrim($tx['image_url'], '/');
        if (file_exists($filePath) && is_file($filePath)) {
            @unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Transaksi beserta bukti foto berhasil dihapus']);
}
