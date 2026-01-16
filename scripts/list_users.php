<?php
$config = require __DIR__ . '/../config/db.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
foreach ($pdo->query('SELECT userid, nama, email, username FROM `user` ORDER BY userid') as $r) {
    echo "userid={$r['userid']}, nama={$r['nama']}, email={$r['email']}, username=" . ($r['username'] ?? 'NULL') . PHP_EOL;
}
