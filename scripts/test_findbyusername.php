<?php
$config = require __DIR__ . '/../config/db.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$q = 'udinpeot';
$stmt = $pdo->prepare('SELECT userid, nama, email, username FROM `user` WHERE username = :q OR email = :q OR nama = :q');
$stmt->execute([':q' => $q]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Query test untuk '" . $q . "' - ditemukan: " . count($rows) . PHP_EOL;
if (count($rows)) {
    foreach ($rows as $r) echo json_encode($r) . PHP_EOL;
}
