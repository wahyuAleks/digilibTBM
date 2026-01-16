<?php

/**
 * Script untuk menambahkan contoh data penulis dan ISBN ke buku
 * Jalankan: php add_sample_book_data.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

echo "========================================\n";
echo "Menambahkan Sample Data Penulis & ISBN\n";
echo "========================================\n\n";

// Data sample untuk setiap kategori
$sampleData = [
    // Fiksi
    ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'isbn' => '978-979-3062-79-2'],
    ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'isbn' => '978-979-461-185-6'],
    ['judul' => 'Negeri 5 Menara', 'penulis' => 'Ahmad Fuadi', 'isbn' => '978-602-8811-43-9'],
    ['judul' => 'Pulang', 'penulis' => 'Tere Liye', 'isbn' => '978-602-220-247-2'],
    ['judul' => 'Hujan', 'penulis' => 'Tere Liye', 'isbn' => '978-602-291-518-0'],
    ['judul' => 'Ayat-Ayat Cinta', 'penulis' => 'Habiburrahman El Shirazy', 'isbn' => '978-979-22-2299-2'],
    
    // Non-Fiksi  
    ['judul' => 'Cloud Computing', 'penulis' => 'Thomas Erl', 'isbn' => '978-0-13-387512-4'],
    ['judul' => 'DevOps Handbook', 'penulis' => 'Gene Kim', 'isbn' => '978-1-942788-00-3'],
    ['judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'isbn' => '978-0-13-235088-4'],
    
    // Pendidikan
    ['judul' => 'Matematika Diskrit', 'penulis' => 'Rinaldi Munir', 'isbn' => '978-979-756-226-5'],
    ['judul' => 'Algoritma dan Pemrograman', 'penulis' => 'Rinaldi Munir', 'isbn' => '978-979-756-398-9'],
    ['judul' => 'Fisika Dasar', 'penulis' => 'Halliday Resnick', 'isbn' => '978-0-471-32000-5'],
    
    // Teknologi
    ['judul' => 'Artificial Intelligence', 'penulis' => 'Stuart Russell', 'isbn' => '978-0-13-604259-4'],
    ['judul' => 'Machine Learning', 'penulis' => 'Tom Mitchell', 'isbn' => '978-0-07-042807-2'],
    ['judul' => 'Deep Learning', 'penulis' => 'Ian Goodfellow', 'isbn' => '978-0-262-03561-3'],
];

$updated = 0;
$db = Yii::$app->db;

foreach ($sampleData as $data) {
    try {
        // Update buku berdasarkan judul (cari buku dengan judul yang mirip)
        $result = $db->createCommand()
            ->update('buku', 
                ['penulis' => $data['penulis'], 'isbn' => $data['isbn']], 
                ['like', 'judul', $data['judul']]
            )
            ->execute();
        
        if ($result > 0) {
            echo "✓ Updated '{$data['judul']}' -> Penulis: {$data['penulis']}, ISBN: {$data['isbn']}\n";
            $updated += $result;
        }
    } catch (Exception $e) {
        echo "✗ Error updating '{$data['judul']}': " . $e->getMessage() . "\n";
    }
}

echo "\n========================================\n";
echo "SELESAI!\n";
echo "Total buku yang diupdate: $updated\n";
echo "========================================\n";
