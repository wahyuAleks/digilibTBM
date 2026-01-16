<?php
/*
  Skrip ini:
  - Mengecek apakah kolom `username` ada di tabel `user`.
  - Jika belum ada, tambah kolom `username` VARCHAR(255) DEFAULT NULL.
  - Isi `username` untuk user yang kosong dengan local-part email (sebelum @).
  - Jika ada duplikat username setelah pengisian, tambahkan suffix `_<userid>` untuk membuatnya unik.
*/

$config = require __DIR__ . '/../config/db.php';
$dsn = $config['dsn'];
$dbuser = $config['username'];
$dbpass = $config['password'];

$pdo = new PDO($dsn, $dbuser, $dbpass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function info($msg) { echo "[info] " . $msg . PHP_EOL; }

// 1) Cek apakah kolom `username` ada
$res = $pdo->query("SHOW COLUMNS FROM `user` LIKE 'username'")->fetch();
if ($res) {
    info("Kolom 'username' sudah ada.");
} else {
    info("Menambahkan kolom 'username' ke tabel 'user'...");
    $pdo->exec("ALTER TABLE `user` ADD `username` VARCHAR(255) DEFAULT NULL AFTER `nama`");
    info("Kolom 'username' berhasil ditambahkan.");
    // Tambahkan index unik jika belum ada
    // Cek apakah index username ada
    $resIdx = $pdo->query("SHOW INDEX FROM `user` WHERE Column_name='username'")->fetch();
    if (!$resIdx) {
        info("Menambahkan UNIQUE INDEX pada kolom 'username'...");
        $pdo->exec("ALTER TABLE `user` ADD UNIQUE (`username`)");
        info("UNIQUE INDEX pada 'username' ditambahkan.");
    }
}

// 2) Isi username yang kosong dengan local-part email
info("Mengisi username yang kosong dari local-part email (sebelum @)...");
$pdo->exec("UPDATE `user` SET `username` = SUBSTRING_INDEX(`email`, '@', 1) WHERE `username` IS NULL OR `username` = ''");
$updated = $pdo->query("SELECT ROW_COUNT() AS rc")->fetchColumn();
info("Baris yang di-update: " . ($updated ?: 0));

// 3) Periksa duplikat username
$dups = $pdo->query("SELECT `username`, COUNT(*) as c FROM `user` GROUP BY `username` HAVING c > 1")->fetchAll();
if (count($dups) === 0) {
    info("Tidak ada duplikat username setelah pengisian.");
} else {
    info("Ditemukan duplikat username, akan memperbaiki dengan menambahkan suffix user id...");
    foreach ($dups as $r) {
        $u = $r['username'];
        $rows = $pdo->prepare("SELECT userid FROM `user` WHERE `username` = :u ORDER BY userid");
        $rows->execute([':u' => $u]);
        $users = $rows->fetchAll(PDO::FETCH_COLUMN);
        // keep the first as-is, suffix the rest
        $first = array_shift($users);
        foreach ($users as $uid) {
            $new = $u . '_' . $uid;
            $stmt = $pdo->prepare("UPDATE `user` SET `username` = :new WHERE userid = :uid");
            $stmt->execute([':new' => $new, ':uid' => $uid]);
            info("Set username untuk userid={$uid} => {$new}");
        }
    }
}

// 4) Verifikasi: lakukan contoh pencarian seperti yang memicu error
$test = 'udinpeot';
$stmt = $pdo->prepare("SELECT * FROM `user` WHERE username = :q OR email = :q OR nama = :q");
try {
    $stmt->execute([':q' => $test]);
    $found = $stmt->fetchAll();
    info("Query test berhasil, baris ditemukan: " . count($found));
} catch (PDOException $e) {
    echo "[error] Query test gagal: " . $e->getMessage() . PHP_EOL;
}

info("Selesai.");
