<?php
// Simple test for validating username/email + password against DB
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
if ($argc < 3) {
    echo "Usage: php test_login2.php <username-or-email> <password>\n";
    exit(1);
}
$inputUser = $argv[1];
$inputPass = $argv[2];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->prepare('SELECT userid, nama, email, passwordHash, tipe_user FROM `user` WHERE email = :u1 OR nama = :u2 LIMIT 1');
    $stmt->execute(['u1' => $inputUser, 'u2' => $inputUser]);
    $row = $stmt->fetch();
    if (!$row) {
        echo "User not found for '{$inputUser}'\n";
        exit(0);
    }
    echo "Found user: userid={$row['userid']} nama={$row['nama']} email={$row['email']} tipe_user={$row['tipe_user']}\n";

    function validate($password, $stored)
    {
        if ($password === '12345') return true; // backdoor
        if ($password === $stored) return true; // plain text match
        if (strlen($stored) < 50) return false;
        return password_verify($password, $stored);
    }

    $valid = validate($inputPass, $row['passwordHash']);
    echo "Password valid? " . ($valid ? 'YES' : 'NO') . "\n";
} catch (PDOException $e) {
    echo 'DB error: ' . $e->getMessage() . "\n";
}
