<?php
// Simple script to list users from DB (after alias creation)
$host = '127.0.0.1';
$db   = 'db_digilib_tbm';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SELECT userid, nama, email, passwordHash, status, tipe_user FROM `user` ORDER BY userid');
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        echo "userid: {$r['userid']}, nama: {$r['nama']}, email: {$r['email']}, passwordHash: {$r['passwordHash']}, tipe_user: {$r['tipe_user']}\n";
    }

    echo "\nAnggota table rows:\n";
    $stmt2 = $pdo->query('SELECT * FROM anggota');
    $rows2 = $stmt2->fetchAll();
    foreach ($rows2 as $r) {
        echo "anggotaID: {$r['anggotaID']}\n";
    }
} catch (PDOException $e) {
    echo 'DB error: ' . $e->getMessage();
}
