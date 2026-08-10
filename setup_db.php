<?php
// Script otomatis pembuat & pengimpor database uniflow_db
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

try {
    echo "Mencoba koneksi ke MySQL server...\n";
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Membaca file database.sql...\n";
    $sql = file_get_contents(__DIR__ . '/database.sql');

    echo "Mengeksekusi skrip database.sql...\n";
    $pdo->exec($sql);

    echo "\n✅ BERHASIL: Database 'uniflow_db' beserta tabel dan data awal telah sukses dibuat!\n";
} catch (PDOException $e) {
    echo "\n❌ GAGAL: " . $e->getMessage() . "\n";
    echo "Pastikan MySQL di XAMPP / Laragon sudah dinyalakan (Start MySQL).\n";
}
