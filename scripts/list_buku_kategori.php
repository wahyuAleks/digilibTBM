<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=db_digilib_tbm;charset=utf8mb4','root','');
foreach($pdo->query('SELECT bukuID, judul FROM buku WHERE kategoriID=1 LIMIT 10') as $r) {
    echo $r['bukuID'] . ': ' . $r['judul'] . PHP_EOL;
}
