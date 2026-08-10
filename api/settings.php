<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM settings WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $settings = $stmt->fetch() ?: [
        'daily_budget' => 100000,
        'savings_target' => 5000000,
        'savings_current' => 2150000,
        'savings_name' => 'Beli Laptop Pro'
    ];

    jsonResponse([
        'success' => true,
        'data' => [
            'dailyBudget' => (float)$settings['daily_budget'],
            'savingsTarget' => (float)$settings['savings_target'],
            'savingsCurrent' => (float)$settings['savings_current'],
            'savingsName' => $settings['savings_name']
        ]
    ]);
}

if ($method === 'POST' || $method === 'PUT') {
    $data = getRequestBody();

    if (isset($data['depositAmount'])) {
        $deposit = (float)$data['depositAmount'];
        $stmt = $pdo->prepare("UPDATE settings SET savings_current = savings_current + ? WHERE user_id = ?");
        $stmt->execute([$deposit, $userId]);
        jsonResponse(['success' => true, 'message' => 'Setoran tabungan berhasil ditambahkan!']);
    }

    $dailyBudget = (float)($data['dailyBudget'] ?? ($data['daily_budget'] ?? 100000));
    $savingsTarget = (float)($data['savingsTarget'] ?? ($data['savings_target'] ?? 5000000));
    $savingsCurrent = (float)($data['savingsCurrent'] ?? ($data['savings_current'] ?? 2150000));
    $savingsName = trim($data['savingsName'] ?? ($data['savings_name'] ?? 'Beli HP Xiaomi 13T'));

    $stmt = $pdo->prepare("INSERT INTO settings (user_id, daily_budget, savings_target, savings_current, savings_name) 
        VALUES (?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE daily_budget = VALUES(daily_budget), savings_target = VALUES(savings_target), savings_current = VALUES(savings_current), savings_name = VALUES(savings_name)");
    $stmt->execute([$userId, $dailyBudget, $savingsTarget, $savingsCurrent, $savingsName]);

    jsonResponse(['success' => true, 'message' => 'Pengaturan anggaran & target tabungan berhasil diperbarui']);
}
