<?php

/**
 * Script untuk menambahkan kolom penulis dan ISBN ke tabel buku
 * Jalankan: php add_penulis_isbn_columns.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "========================================\n";
echo "Menambahkan Kolom Penulis & ISBN\n";
echo "========================================\n\n";

try {
    $db = Yii::$app->db;
    
    // Cek apakah kolom sudah ada
    $tableSchema = $db->getTableSchema('buku');
    $columns = array_keys($tableSchema->columns);
    
    // Tambah kolom penulis jika belum ada
    if (!in_array('penulis', $columns)) {
        echo "Menambahkan kolom 'penulis'...\n";
        $db->createCommand("ALTER TABLE `buku` ADD COLUMN `penulis` VARCHAR(255) NULL AFTER `judul`")->execute();
        echo "✓ Kolom 'penulis' berhasil ditambahkan\n\n";
    } else {
        echo "✓ Kolom 'penulis' sudah ada\n\n";
    }
    
    // Tambah kolom isbn jika belum ada
    if (!in_array('isbn', $columns)) {
        echo "Menambahkan kolom 'isbn'...\n";
        $db->createCommand("ALTER TABLE `buku` ADD COLUMN `isbn` VARCHAR(50) NULL AFTER `penulis`")->execute();
        echo "✓ Kolom 'isbn' berhasil ditambahkan\n\n";
    } else {
        echo "✓ Kolom 'isbn' sudah ada\n\n";
    }
    
    echo "========================================\n";
    echo "SELESAI! Database berhasil diupdate.\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
