<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC");
    $stmt->execute([$userId]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $data = getRequestBody();
    $type = $data['type'] ?? 'expense';
    $amount = (float)($data['amount'] ?? 0);
    $title = trim($data['title'] ?? '');
    $category = $data['category'] ?? 'Makanan';
    $date = $data['date'] ?? date('Y-m-d');

    if ($amount <= 0 || empty($title)) {
        jsonResponse(['success' => false, 'message' => 'Jumlah saldo dan keterangan transaksi wajib diisi'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, title, category, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $type, $amount, $title, $category, $date]);
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
            'date' => $date
        ]
    ]);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Transaksi wajib disertakan'], 400);
    }

    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
}
