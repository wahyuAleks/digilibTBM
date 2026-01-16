<?php
$config = require __DIR__ . '/../config/db.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
foreach ($pdo->query('SELECT * FROM `anggota` ORDER BY anggotaID') as $r) {
    echo json_encode($r) . PHP_EOL;
}
