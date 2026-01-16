<?php
// Adds alias users admin@gmail.com and anggota@gmail.com with password 12345
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
    $password = '12345';
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Admin alias
    $stmt = $pdo->prepare('INSERT INTO `user` (`nama`,`email`,`passwordHash`,`status`,`tipe_user`) VALUES (:nama,:email,:hash,:status,:tipe) ON DUPLICATE KEY UPDATE `nama`=VALUES(`nama`), `passwordHash`=VALUES(`passwordHash`), `status`=VALUES(`status`), `tipe_user`=VALUES(`tipe_user`)');
    $stmt->execute(['nama'=>'Administrator','email'=>'admin@gmail.com','hash'=>$hash,'status'=>'aktif','tipe'=>'admin']);

    // Anggota alias
    $stmt->execute(['nama'=>'Anggota Demo','email'=>'anggota@gmail.com','hash'=>$hash,'status'=>'aktif','tipe'=>'anggota']);

    // Ensure anggota table has a row for anggota@gmail.com (userid as anggotaID)
    $stmt = $pdo->prepare('SELECT userid FROM `user` WHERE email = :email LIMIT 1');
    $stmt->execute(['email'=>'anggota@gmail.com']);
    $row = $stmt->fetch();
    if ($row) {
        $userid = (int)$row['userid'];
        $stmt2 = $pdo->prepare('INSERT IGNORE INTO `anggota` (`anggotaID`) VALUES (:id)');
        $stmt2->execute(['id'=>$userid]);
    }

    echo "Alias users created/updated.\n";
} catch (PDOException $e) {
    echo 'DB error: ' . $e->getMessage() . "\n";
}
