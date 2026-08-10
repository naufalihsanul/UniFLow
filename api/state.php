<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    try {
        // 1. Tasks
        $stmtTasks = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY date ASC, time ASC");
        $stmtTasks->execute([$userId]);
        $tasks = $stmtTasks->fetchAll();
        foreach ($tasks as &$t) {
            $t['completed'] = (bool)$t['completed'];
        }

        // 2. Folders
        $stmtFolders = $pdo->prepare("SELECT * FROM folders WHERE user_id = ? ORDER BY id ASC");
        $stmtFolders->execute([$userId]);
        $folders = $stmtFolders->fetchAll();

        // 3. Notes
        $stmtNotes = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY is_pinned DESC, updated_at DESC");
        $stmtNotes->execute([$userId]);
        $notes = $stmtNotes->fetchAll();
        foreach ($notes as &$n) {
            $n['isPinned'] = (bool)$n['is_pinned'];
            $n['folderId'] = $n['folder_id'];
            $n['updatedAt'] = $n['updated_at'];
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

        // 4. Transactions
        $stmtTx = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC");
        $stmtTx->execute([$userId]);
        $transactions = $stmtTx->fetchAll();

        // 5. Habits
        $stmtHabits = $pdo->prepare("SELECT * FROM habits WHERE user_id = ? ORDER BY id ASC");
        $stmtHabits->execute([$userId]);
        $habits = $stmtHabits->fetchAll();
        foreach ($habits as &$h) {
            $h['completedToday'] = (bool)$h['completed_today'];
        }

        // 6. Settings
        $stmtSettings = $pdo->prepare("SELECT * FROM settings WHERE user_id = ? LIMIT 1");
        $stmtSettings->execute([$userId]);
        $settings = $stmtSettings->fetch() ?: [
            'daily_budget' => 100000,
            'savings_target' => 5000000,
            'savings_current' => 2150000,
            'savings_name' => 'Beli Laptop Pro'
        ];

        jsonResponse([
            'success' => true,
            'data' => [
                'tasks' => $tasks,
                'folders' => $folders,
                'notes' => $notes,
                'transactions' => $transactions,
                'habits' => $habits,
                'settings' => [
                    'dailyBudget' => (float)$settings['daily_budget'],
                    'savingsTarget' => (float)$settings['savings_target'],
                    'savingsCurrent' => (float)$settings['savings_current'],
                    'savingsName' => $settings['savings_name']
                ]
            ]
        ]);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
