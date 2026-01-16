<?php

/**
 * Script untuk menambahkan kolom thumbnail ke tabel buku
 * dan mengupdate semua buku kategori Fiksi dengan gambar
 */

// Autoload Yii
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Load konfigurasi
$config = require __DIR__ . '/config/web.php';

// Buat aplikasi
$application = new yii\web\Application($config);

try {
    $db = Yii::$app->db;
    
    echo "=== UPDATE THUMBNAIL BUKU FIKSI ===\n\n";
    
    // 1. Cek apakah kolom thumbnail sudah ada
    $tableSchema = $db->getTableSchema('buku');
    $columns = array_keys($tableSchema->columns);
    
    if (!in_array('thumbnail', $columns)) {
        echo "Step 1: Menambahkan kolom 'thumbnail' ke tabel buku...\n";
        $db->createCommand("ALTER TABLE `buku` ADD COLUMN `thumbnail` VARCHAR(255) NULL DEFAULT NULL AFTER `stok`")->execute();
        echo "✓ Kolom 'thumbnail' berhasil ditambahkan!\n\n";
    } else {
        echo "Step 1: Kolom 'thumbnail' sudah ada.\n\n";
    }
    
    // 2. Cari kategori Fiksi
    echo "Step 2: Mencari kategori Fiksi...\n";
    $kategori = $db->createCommand("SELECT kategoriID FROM kategori WHERE nama = 'Fiksi'")->queryOne();
    
    if (!$kategori) {
        echo "✗ Kategori Fiksi tidak ditemukan!\n";
        exit(1);
    }
    
    $kategoriID = $kategori['kategoriID'];
    echo "✓ Kategori Fiksi ditemukan dengan ID: {$kategoriID}\n\n";
    
    // 3. Update semua buku kategori Fiksi
    echo "Step 3: Mengupdate thumbnail untuk semua buku kategori Fiksi...\n";
    $result = $db->createCommand("
        UPDATE `buku` 
        SET `thumbnail` = 'fiksi-cover.jpg' 
        WHERE `kategoriID` = :kategoriID
    ", [':kategoriID' => $kategoriID])->execute();
    
    echo "✓ {$result} buku kategori Fiksi berhasil diupdate!\n\n";
    
    // 4. Tampilkan hasil
    echo "Step 4: Menampilkan buku-buku yang sudah diupdate...\n";
    $bukuList = $db->createCommand("
        SELECT b.bukuID, b.judul, b.thumbnail, k.nama as kategori 
        FROM buku b 
        LEFT JOIN kategori k ON b.kategoriID = k.kategoriID 
        WHERE b.kategoriID = :kategoriID
    ", [':kategoriID' => $kategoriID])->queryAll();
    
    if (count($bukuList) > 0) {
        echo "\nDaftar buku yang berhasil diupdate:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-5s | %-50s | %-20s\n", "ID", "Judul", "Thumbnail");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($bukuList as $buku) {
            printf("%-5s | %-50s | %-20s\n", 
                $buku['bukuID'], 
                substr($buku['judul'], 0, 50), 
                $buku['thumbnail']
            );
        }
        echo str_repeat("-", 80) . "\n";
    } else {
        echo "⚠ Tidak ada buku kategori Fiksi di database.\n";
    }
    
    echo "\n=== SELESAI ===\n";
    echo "Thumbnail berhasil ditambahkan untuk semua buku kategori Fiksi!\n";
    echo "File gambar: web/images/fiksi-cover.jpg\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}
