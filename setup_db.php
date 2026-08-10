<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

try {
    echo "Mencoba koneksi ke MySQL server...\n";
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Membaca dan mengeksekusi skrip database.sql...\n";
    $sql = file_get_contents(__DIR__ . '/database.sql');
    $pdo->exec($sql);

    // Ensure transactions image_url column exists
    try {
        $pdo->exec("ALTER TABLE `uniflow_db`.`transactions` ADD COLUMN `image_url` VARCHAR(255) DEFAULT NULL;");
    } catch (Exception $e) {
        // Column may already exist
    }

    echo "\n✅ BERHASIL: Database 'uniflow_db' dan kolom image_url pada tabel transactions telah sukses disiapkan!\n";
} catch (PDOException $e) {
    echo "\n❌ GAGAL: " . $e->getMessage() . "\n";
}
