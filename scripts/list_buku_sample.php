<?php
$pdo=new PDO('mysql:host=127.0.0.1;dbname=db_digilib_tbm;charset=utf8mb4','root','');
foreach($pdo->query('SELECT bukuID,kategoriID,judul FROM buku WHERE kategoriID IN (1,2,3,4) LIMIT 20') as $r) {
    echo $r['bukuID'] . '|' . $r['kategoriID'] . '|' . $r['judul'] . PHP_EOL;
}
