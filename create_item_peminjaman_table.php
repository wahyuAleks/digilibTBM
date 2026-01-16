<?php
/**
 * Script untuk membuat tabel item_peminjaman
 * Jalankan script ini sekali untuk membuat tabel yang diperlukan
 * 
 * Cara menjalankan:
 * 1. Buka terminal/command prompt
 * 2. Masuk ke folder project
 * 3. Jalankan: php create_item_peminjaman_table.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

try {
    $db = \Yii::$app->db;
    
    // Cek apakah tabel sudah ada
    $tableSchema = $db->getTableSchema('item_peminjaman');
    if ($tableSchema) {
        echo "Tabel item_peminjaman sudah ada.\n";
        exit(0);
    }
    
    // Cek primary key tabel peminjaman
    $peminjamanSchema = $db->getTableSchema('peminjaman');
    $peminjamanPK = 'id';
    if ($peminjamanSchema) {
        $pks = $peminjamanSchema->primaryKey;
        if (!empty($pks)) {
            $peminjamanPK = $pks[0];
        }
    }
    
    // Cek primary key tabel buku
    $bukuSchema = $db->getTableSchema('buku');
    $bukuPK = 'bukuID';
    if ($bukuSchema) {
        $pks = $bukuSchema->primaryKey;
        if (!empty($pks)) {
            $bukuPK = $pks[0];
        }
    }
    
    // Buat tabel tanpa foreign key constraint dulu (untuk menghindari error jika primary key berbeda)
    $sql = "CREATE TABLE IF NOT EXISTS `item_peminjaman` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `peminjamanID` int(11) NOT NULL,
      `bukuID` int(11) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `idx_peminjamanID` (`peminjamanID`),
      KEY `idx_bukuID` (`bukuID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->createCommand($sql)->execute();
    
    echo "✓ Tabel item_peminjaman berhasil dibuat!\n";
    echo "Primary key peminjaman: {$peminjamanPK}\n";
    echo "Primary key buku: {$bukuPK}\n";
    echo "\nTabel siap digunakan. Anda dapat mengakses aplikasi sekarang.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

