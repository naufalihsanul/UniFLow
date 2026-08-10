-- Database setup for UniFlow Productivity App
CREATE DATABASE IF NOT EXISTS `uniflow_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uniflow_db`;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Folders Table
CREATE TABLE IF NOT EXISTS `folders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL DEFAULT 1,
  `name` VARCHAR(100) NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `folders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tasks Table
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL DEFAULT 1,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Kuliah',
  `priority` VARCHAR(20) NOT NULL DEFAULT 'Sedang',
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `completed` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Notes Table
CREATE TABLE IF NOT EXISTS `notes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL DEFAULT 1,
  `folder_id` INT DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `image_url` TEXT DEFAULT NULL,
  `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`folder_id`) REFERENCES `folders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Transactions Table (With optional receipt photo)
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL DEFAULT 1,
  `type` ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
  `amount` DECIMAL(12, 2) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Makanan',
  `date` DATE NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Habits Table
CREATE TABLE IF NOT EXISTS `habits` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL DEFAULT 1,
  `title` VARCHAR(255) NOT NULL,
  `streak` INT NOT NULL DEFAULT 0,
  `completed_today` TINYINT(1) NOT NULL DEFAULT 0,
  `last_completed_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL UNIQUE DEFAULT 1,
  `daily_budget` DECIMAL(12, 2) NOT NULL DEFAULT 100000,
  `savings_target` DECIMAL(12, 2) NOT NULL DEFAULT 5000000,
  `savings_current` DECIMAL(12, 2) NOT NULL DEFAULT 2150000,
  `savings_name` VARCHAR(100) NOT NULL DEFAULT 'Beli Laptop Pro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Initial Demo User
INSERT IGNORE INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'mahasiswa', 'mahasiswa@uniflow.app', '$2y$10$wO3l.jWp/02uO8qF9gJd1eXzH91Y4GZtJjV59oZ4H4L4k7zG8gH1S');

-- Seed Initial Folders
INSERT IGNORE INTO `folders` (`id`, `user_id`, `name`, `parent_id`) VALUES
(1, 1, 'Semester 6', NULL),
(2, 1, 'HMIF Organisasi', NULL),
(3, 1, 'Catatan Pribadi', NULL);

-- Seed Initial Tasks
INSERT IGNORE INTO `tasks` (`id`, `user_id`, `title`, `category`, `priority`, `date`, `time`, `completed`) VALUES
(1, 1, 'Kuliah Pemrograman Web', 'Kuliah', 'Tinggi', CURDATE(), '08:00:00', 0),
(2, 1, 'Rapat Laporan Akhir HMIF', 'Organisasi', 'Sedang', CURDATE(), '13:30:00', 0),
(3, 1, 'Kumpul Tugas Algoritma', 'Kuliah', 'Tinggi', CURDATE(), '23:59:00', 0),
(4, 1, 'Beli Buku Catatan Baru', 'Pribadi', 'Rendah', CURDATE(), '16:00:00', 1);

-- Seed Initial Notes
INSERT IGNORE INTO `notes` (`id`, `user_id`, `folder_id`, `title`, `content`, `image_url`, `is_pinned`) VALUES
(1, 1, 1, 'Resume Web Design Principles', 'Gunakan spacing konsisten, Apple style menyukai minimalis dan kontras jelas.', NULL, 1),
(2, 1, 2, 'Agenda Makrab 2026', '1. Pembukaan\n2. Games Interaktif\n3. Sesi Diskusi Mahasiswa', NULL, 0);

-- Seed Initial Transactions
INSERT IGNORE INTO `transactions` (`id`, `user_id`, `type`, `amount`, `title`, `category`, `date`) VALUES
(1, 1, 'expense', 18000.00, 'Nasi Goreng Kantin', 'Makanan', CURDATE()),
(2, 1, 'expense', 5000.00, 'Print Tugas Kuliah', 'Akademik', CURDATE()),
(3, 1, 'expense', 50000.00, 'Isi Saldo E-Money', 'Transport', CURDATE()),
(4, 1, 'income', 500000.00, 'Transfer Orang Tua', 'Pemasukan', CURDATE());

-- Seed Initial Habits
INSERT IGNORE INTO `habits` (`id`, `user_id`, `title`, `streak`, `completed_today`) VALUES
(1, 1, 'Baca Jurnal 20 Menit', 4, 1),
(2, 1, 'Minum 2 Liter Air', 7, 1),
(3, 1, 'Review Materi Kuliah', 2, 0);

-- Seed Initial Settings
INSERT IGNORE INTO `settings` (`id`, `user_id`, `daily_budget`, `savings_target`, `savings_current`, `savings_name`) VALUES
(1, 1, 100000.00, 5000000.00, 2150000.00, 'Beli Laptop Pro');
