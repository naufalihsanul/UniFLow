<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM habits WHERE user_id = ? ORDER BY id ASC");
    $stmt->execute([$userId]);
    $habits = $stmt->fetchAll();
    foreach ($habits as &$h) {
        $h['completedToday'] = (bool)$h['completed_today'];
    }
    jsonResponse(['success' => true, 'data' => $habits]);
}

if ($method === 'POST') {
    $data = getRequestBody();
    $title = trim($data['title'] ?? '');

    if (empty($title)) {
        jsonResponse(['success' => false, 'message' => 'Nama kebiasaan wajib diisi'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO habits (user_id, title, streak, completed_today) VALUES (?, ?, 0, 0)");
    $stmt->execute([$userId, $title]);
    $id = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Kebiasaan baru ditambahkan',
        'data' => ['id' => $id, 'title' => $title, 'streak' => 0, 'completedToday' => false]
    ]);
}

if ($method === 'PUT') {
    $data = getRequestBody();
    $id = $data['id'] ?? null;

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Kebiasaan tidak valid'], 400);
    }

    $stmtSelect = $pdo->prepare("SELECT * FROM habits WHERE id = ? AND user_id = ?");
    $stmtSelect->execute([$id, $userId]);
    $habit = $stmtSelect->fetch();

    if ($habit) {
        $newCompleted = $habit['completed_today'] ? 0 : 1;
        $newStreak = $newCompleted ? ($habit['streak'] + 1) : max(0, $habit['streak'] - 1);
        $today = date('Y-m-d');

        $stmt = $pdo->prepare("UPDATE habits SET completed_today = ?, streak = ?, last_completed_date = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$newCompleted, $newStreak, $today, $id, $userId]);
    }

    jsonResponse(['success' => true, 'message' => 'Status kebiasaan diperbarui']);
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? (getRequestBody()['id'] ?? null);
    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'ID Kebiasaan wajib disertakan'], 400);
    }

    $stmt = $pdo->prepare("DELETE FROM habits WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);
    jsonResponse(['success' => true, 'message' => 'Kebiasaan berhasil dihapus']);
}
