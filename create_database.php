<?php
/**
 * Script untuk membuat ulang database db_digilib_tbm
 * Jalankan script ini untuk membuat database dan semua tabel yang diperlukan
 * 
 * Cara menjalankan:
 * 1. Buka terminal/command prompt
 * 2. Masuk ke folder project
 * 3. Jalankan: php create_database.php
 * 
 * PERINGATAN: Script ini akan membuat database baru. 
 * Jika database sudah ada, tabel-tabel akan dibuat jika belum ada.
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "========================================\n";
echo "Membuat Database db_digilib_tbm\n";
echo "========================================\n\n";

try {
    $db = \Yii::$app->db;
    
    // Baca konfigurasi database
    $dsn = $db->dsn;
    preg_match('/host=([^;]+)/', $dsn, $hostMatch);
    preg_match('/dbname=([^;]+)/', $dsn, $dbnameMatch);
    $host = $hostMatch[1] ?? 'localhost';
    $dbname = $dbnameMatch[1] ?? 'db_digilib_tbm';
    $username = $db->username;
    $password = $db->password;
    
    echo "Konfigurasi Database:\n";
    echo "  Host: {$host}\n";
    echo "  Database: {$dbname}\n";
    echo "  Username: {$username}\n\n";
    
    // Koneksi tanpa database untuk membuat database
    $pdo = new PDO("mysql:host={$host}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database jika belum ada
    echo "1. Membuat database '{$dbname}'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "   ✓ Database berhasil dibuat/ditemukan\n\n";
    
    // Gunakan database
    $pdo->exec("USE `{$dbname}`");
    
    // Buat tabel user
    echo "2. Membuat tabel 'user'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user` (
      `userid` int(11) NOT NULL AUTO_INCREMENT,
      `nama` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `passwordHash` varchar(255) NOT NULL,
      `status` varchar(50) DEFAULT NULL,
      `tipe_user` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`userid`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel user berhasil dibuat\n\n";
    
    // Buat tabel anggota
    echo "3. Membuat tabel 'anggota'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `anggota` (
      `anggotaID` int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (`anggotaID`),
      CONSTRAINT `fk_anggota_user` FOREIGN KEY (`anggotaID`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel anggota berhasil dibuat\n\n";
    
    // Buat tabel kategori
    echo "4. Membuat tabel 'kategori'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `kategori` (
      `kategoriID` int(11) NOT NULL AUTO_INCREMENT,
      `nama` varchar(255) NOT NULL,
      PRIMARY KEY (`kategoriID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel kategori berhasil dibuat\n\n";
    
    // Buat tabel rak
    echo "5. Membuat tabel 'rak'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rak` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nama` varchar(255) NOT NULL,
      `lokasi` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel rak berhasil dibuat\n\n";
    
    // Buat tabel buku
    echo "6. Membuat tabel 'buku'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `buku` (
      `bukuID` int(11) NOT NULL AUTO_INCREMENT,
      `kategoriID` int(11) NOT NULL,
      `rakID` int(11) DEFAULT NULL,
      `judul` varchar(255) NOT NULL,
      `stok` int(11) DEFAULT NULL,
      PRIMARY KEY (`bukuID`),
      KEY `idx_kategoriID` (`kategoriID`),
      KEY `idx_rakID` (`rakID`),
      CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`kategoriID`) REFERENCES `kategori` (`kategoriID`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `fk_buku_rak` FOREIGN KEY (`rakID`) REFERENCES `rak` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel buku berhasil dibuat\n\n";
    
    // Buat tabel peminjaman
    echo "7. Membuat tabel 'peminjaman'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `peminjaman` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `anggotaID` int(11) NOT NULL,
      `tanggalPinjam` datetime DEFAULT NULL,
      `tanggalKembali` datetime DEFAULT NULL,
      `tglJatuhTempo` datetime DEFAULT NULL,
      `status` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_anggotaID` (`anggotaID`),
      CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`anggotaID`) REFERENCES `anggota` (`anggotaID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel peminjaman berhasil dibuat\n\n";
    
    // Buat tabel item_peminjaman
    echo "8. Membuat tabel 'item_peminjaman'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `item_peminjaman` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `peminjamanID` int(11) NOT NULL,
      `bukuID` int(11) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_peminjamanID` (`peminjamanID`),
      KEY `idx_bukuID` (`bukuID`),
      CONSTRAINT `fk_item_peminjaman_peminjaman` FOREIGN KEY (`peminjamanID`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `fk_item_peminjaman_buku` FOREIGN KEY (`bukuID`) REFERENCES `buku` (`bukuID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel item_peminjaman berhasil dibuat\n\n";
    
    // Buat tabel denda
    echo "9. Membuat tabel 'denda'...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `denda` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `peminjamanID` int(11) NOT NULL,
      `jumlah` decimal(10,2) NOT NULL,
      `hariTerlambat` int(11) NOT NULL,
      `tanggalDibuat` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_peminjamanID` (`peminjamanID`),
      CONSTRAINT `fk_denda_peminjaman` FOREIGN KEY (`peminjamanID`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "   ✓ Tabel denda berhasil dibuat\n\n";
    
    // Insert data sample (opsional)
    echo "10. Memasukkan data sample...\n";
    
    // Kategori
    $pdo->exec("INSERT INTO `kategori` (`kategoriID`, `nama`) VALUES
      (1, 'Fiksi'),
      (2, 'Non-Fiksi'),
      (3, 'Pendidikan'),
      (4, 'Teknologi')
    ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`)");
    echo "   ✓ Data kategori sample dimasukkan\n";
    
    // Rak
    $pdo->exec("INSERT INTO `rak` (`id`, `nama`, `lokasi`) VALUES
      (1, 'Rak A', 'Lantai 1'),
      (2, 'Rak B', 'Lantai 1'),
      (3, 'Rak C', 'Lantai 2')
    ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `lokasi` = VALUES(`lokasi`)");
    echo "   ✓ Data rak sample dimasukkan\n";
    
    // User admin (password: 12345)
    $pdo->exec("INSERT INTO `user` (`userid`, `nama`, `email`, `passwordHash`, `status`, `tipe_user`) VALUES
      (1, 'Administrator', 'admin@digilib.com', '12345', 'aktif', 'admin')
    ON DUPLICATE KEY UPDATE `nama` = VALUES(`nama`), `email` = VALUES(`email`)");
    echo "   ✓ Data user admin sample dimasukkan\n\n";
    
    echo "========================================\n";
    echo "✓ Database berhasil dibuat dengan lengkap!\n";
    echo "========================================\n\n";
    echo "Tabel yang dibuat:\n";
    echo "  1. user\n";
    echo "  2. anggota\n";
    echo "  3. kategori\n";
    echo "  4. rak\n";
    echo "  5. buku\n";
    echo "  6. peminjaman\n";
    echo "  7. item_peminjaman\n";
    echo "  8. denda\n\n";
    echo "Data sample juga telah dimasukkan.\n";
    echo "Anda dapat mengakses aplikasi sekarang.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

