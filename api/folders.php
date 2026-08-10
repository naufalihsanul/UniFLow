<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM folders WHERE user_id = ? ORDER BY id ASC");
    $stmt->execute([$userId]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

if ($method === 'POST') {
    $data = getRequestBody();
    $name = trim($data['name'] ?? '');
    $parentId = $data['parentId'] ?? null;

    if (empty($name)) {
        jsonResponse(['success' => false, 'message' => 'Nama folder wajib diisi'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO folders (user_id, name, parent_id) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $name, $parentId ?: null]);
    $id = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Folder berhasil dibuat',
        'data' => ['id' => $id, 'name' => $name, 'parentId' => $parentId]
    ]);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Folder wajib disertakan'], 400);
    }

    $stmt = $pdo->prepare("DELETE FROM folders WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Folder beserta isinya berhasil dihapus']);
}
