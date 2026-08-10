<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY date ASC, time ASC");
    $stmt->execute([$userId]);
    $tasks = $stmt->fetchAll();
    foreach ($tasks as &$t) {
        $t['completed'] = (bool)$t['completed'];
    }
    jsonResponse(['success' => true, 'data' => $tasks]);
}

if ($method === 'POST') {
    $data = getRequestBody();
    $title = trim($data['title'] ?? '');
    $category = $data['category'] ?? 'Kuliah';
    $priority = $data['priority'] ?? 'Sedang';
    $date = $data['date'] ?? date('Y-m-d');
    $time = $data['time'] ?? date('H:i:s');

    if (empty($title)) {
        jsonResponse(['success' => false, 'message' => 'Judul kegiatan wajib diisi'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, category, priority, date, time, completed) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$userId, $title, $category, $priority, $date, $time]);
    $id = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Tugas berhasil disimpan',
        'data' => [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'priority' => $priority,
            'date' => $date,
            'time' => $time,
            'completed' => false
        ]
    ]);
}

if ($method === 'PUT') {
    $data = getRequestBody();
    $id = $data['id'] ?? null;
    $completed = isset($data['completed']) ? ($data['completed'] ? 1 : 0) : null;

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Tugas tidak ditemukan'], 400);
    }

    if ($completed !== null) {
        $stmt = $pdo->prepare("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$completed, $id, $userId]);
    }

    jsonResponse(['success' => true, 'message' => 'Status tugas diperbarui']);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Tugas wajib disertakan'], 400);
    }

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Tugas berhasil dihapus']);
}
