<?php
$config = require __DIR__ . '/../config/db.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function info($m){ echo "[info] $m" . PHP_EOL; }

$users = $pdo->query("SELECT userid, email, username FROM `user` WHERE username IS NULL OR username = ''")->fetchAll(PDO::FETCH_ASSOC);
if (!count($users)) {
    info("Tidak ada user kosong username.");
    exit;
}
$pdo->beginTransaction();
try {
    foreach ($users as $u) {
        $userid = $u['userid'];
        $email = $u['email'];
        $base = preg_replace('/[^a-z0-9_\-]/i', '', explode('@', $email)[0]);
        if (!$base) $base = 'user';
        // cek apakah ada user lain yang menggunakan base
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `user` WHERE username = :base");
        $stmt->execute([':base' => $base]);
        $count = (int)$stmt->fetchColumn();
        if ($count > 0) {
            $new = $base . '_' . $userid;
        } else {
            $new = $base;
        }
        $up = $pdo->prepare("UPDATE `user` SET username = :new WHERE userid = :uid");
        $up->execute([':new' => $new, ':uid' => $userid]);
        info("userid={$userid} => username={$new}");
    }
    $pdo->commit();
    info("Selesai memperbarui username.");
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "[error] " . $e->getMessage() . PHP_EOL;
}
