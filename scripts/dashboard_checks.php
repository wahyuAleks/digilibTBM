<?php
// Quick checks to mirror SiteController::actionDashboard calculations
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

    // Total buku
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM buku');
    $totalBuku = (int)$stmt->fetchColumn();

    // Stok tersedia (sum stok > 0)
    $stmt = $pdo->query('SELECT SUM(stok) as s FROM buku WHERE stok > 0');
    $bukuTersedia = (int)($stmt->fetchColumn() ?? 0);

    // Total anggota
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM anggota');
    $totalAnggota = (int)$stmt->fetchColumn();

    // Peminjaman aktif (status dipinjam or menunggu_verifikasi_admin)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM peminjaman WHERE status IN ('dipinjam','menunggu_verifikasi_admin')");
    $stmt->execute();
    $peminjamanAktif = (int)$stmt->fetchColumn();

    // Total denda (sum jumlah if column exists)
    $totalDenda = 0.0;
    $stmt = $pdo->query("SHOW COLUMNS FROM denda LIKE 'jumlah'");
    if ($stmt->fetch()) {
        $stmt2 = $pdo->query('SELECT SUM(jumlah) FROM denda');
        $totalDenda = (float)($stmt2->fetchColumn() ?? 0);
    }

    echo "totalBuku: $totalBuku\n";
    echo "bukuTersedia (sum stok>0): $bukuTersedia\n";
    echo "totalAnggota: $totalAnggota\n";
    echo "peminjamanAktif: $peminjamanAktif\n";
    echo "totalDenda: $totalDenda\n";
} catch (PDOException $e) {
    echo 'DB error: ' . $e->getMessage() . "\n";
}
