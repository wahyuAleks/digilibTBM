<?php
/* @var $this yii\web\View */
/* @var $totalBuku int */
/* @var $bukuTersedia int */
/* @var $totalAnggota int */
/* @var $anggotaAktif int */
/* @var $peminjamanAktif int */
/* @var $totalDenda float */
/* @var $bukuTerlambat int */
/* @var $peminjamanTerbaru app\models\Peminjaman[] */
/* @var $bukuStokRendah int */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Dashboard Admin';
$user = Yii::$app->user->identity;
?>

<div class="pt-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang, <?= Html::encode($user->nama ?? 'Administrator') ?></h1>
        <p class="text-gray-600">Berikut adalah ringkasan aktivitas perpustakaan hari ini</p>
    </div>

    <!-- Notifikasi Tabel item_peminjaman tidak ada -->
    <?php
    $itemPeminjamanExists = false;
    try {
        $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
        $itemPeminjamanExists = $tableSchema !== null;
    } catch (\Exception $e) {
        $itemPeminjamanExists = false;
    }
    ?>
    <?php if (!$itemPeminjamanExists): ?>
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-red-800">
                    <strong>Peringatan:</strong> Tabel <code>item_peminjaman</code> tidak ditemukan di database. Beberapa fitur mungkin tidak berfungsi dengan baik.
                </p>
            </div>
            <div class="ml-4">
                <a href="<?= Url::to(['/site/create-item-peminjaman-table']) ?>" class="text-sm font-medium text-red-800 hover:text-red-900 underline bg-red-100 px-4 py-2 rounded-lg">
                    Buat Tabel Sekarang →
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Notifikasi Pinjaman Menunggu Verifikasi -->
    <?php if (isset($menungguVerifikasi) && $menungguVerifikasi > 0): ?>
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-yellow-800">
                    Ada <strong><?= $menungguVerifikasi ?></strong> pinjaman yang menunggu verifikasi admin.
                </p>
            </div>
            <div class="ml-4">
                <a href="<?= Url::to(['/peminjaman/index', 'tab' => 'aktif']) ?>" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                    Verifikasi Sekarang →
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <a href="<?= Url::to(['/buku/index']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Total Buku</span>
                <div class="text-blue-500 bg-blue-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $totalBuku ?></span>
                <p class="text-xs text-gray-400 mt-1"><?= $bukuTersedia ?> tersedia</p>
            </div>
        </a>

        <a href="<?= Url::to(['/anggota/index']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Anggota Aktif</span>
                <div class="text-green-500 bg-green-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $anggotaAktif ?></span>
                <p class="text-xs text-gray-400 mt-1">dari <?= $totalAnggota ?> total anggota</p>
            </div>
        </a>

        <a href="<?= Url::to(['/peminjaman/index']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Sedang Dipinjam</span>
                <div class="text-purple-500 bg-purple-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $peminjamanAktif ?></span>
                <p class="text-xs text-gray-400 mt-1">peminjaman aktif</p>
            </div>
        </a>

        <a href="<?= Url::to(['/peminjaman/index']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Total Denda</span>
                <div class="text-yellow-500 bg-yellow-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800">Rp <?= number_format($totalDenda, 0, ',', '.') ?></span>
                <p class="text-xs text-gray-400 mt-1"><?= $bukuTerlambat ?> buku terlambat</p>
            </div>
        </a>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Peminjaman Terbaru -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Peminjaman Terbaru</h3>
                    <p class="text-sm text-gray-500">5 peminjaman terakhir</p>
                </div>
            </div>

            <div class="space-y-4">
                <?php if (empty($peminjamanTerbaru)): ?>
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada peminjaman</p>
                <?php else: ?>
                    <?php foreach ($peminjamanTerbaru as $peminjaman): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex-1">
                            <?php 
                            $bukuJudul = '-';
                            if ($peminjaman->itemPeminjamans) {
                                $item = $peminjaman->itemPeminjamans[0];
                                if ($item->buku) {
                                    $bukuJudul = $item->buku->judul;
                                }
                            }
                            ?>
                            <h4 class="text-sm font-medium text-gray-800"><?= Html::encode($bukuJudul) ?></h4>
                            <?php
                            $anggotaNama = 'N/A';
                            if ($peminjaman->anggota) {
                                // Cek apakah kolom nama ada di anggota
                                if (isset($peminjaman->anggota->nama)) {
                                    $anggotaNama = $peminjaman->anggota->nama;
                                } elseif ($peminjaman->anggota->user && isset($peminjaman->anggota->user->nama)) {
                                    // Jika tidak ada, ambil dari relasi user
                                    $anggotaNama = $peminjaman->anggota->user->nama;
                                } elseif ($peminjaman->anggota->user && isset($peminjaman->anggota->user->email)) {
                                    // Fallback ke email jika nama tidak ada
                                    $anggotaNama = $peminjaman->anggota->user->email;
                                }
                            }
                            ?>
                            <p class="text-xs text-gray-500 mt-0.5"><?= Html::encode($anggotaNama) ?></p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <?php
                            $statusClass = 'bg-gray-100 text-gray-600';
                            $statusText = $peminjaman->status ?? 'Unknown';
                            
                            if ($statusText === 'dipinjam') {
                                $statusClass = 'bg-blue-100 text-blue-600';
                                $statusText = 'Dipinjam';
                            } elseif ($statusText === 'terlambat' || ($peminjaman->tglJatuhTempo && strtotime($peminjaman->tglJatuhTempo) < time() && $statusText !== 'dikembalikan')) {
                                // Cek terlambat: jatuh tempo sudah lewat dan status bukan dikembalikan
                                $statusClass = 'bg-red-100 text-red-600';
                                $statusText = 'Terlambat';
                            } elseif ($statusText === 'dikembalikan') {
                                $statusClass = 'bg-green-100 text-green-600';
                                $statusText = 'Dikembalikan';
                            } elseif ($statusText === 'menunggu_verifikasi_admin') {
                                $statusClass = 'bg-yellow-100 text-yellow-600';
                                $statusText = 'Menunggu Verifikasi';
                            }
                            ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                <?= Html::encode($statusText) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Peringatan -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Peringatan</h3>
            <p class="text-sm text-gray-500 mb-6">Perlu perhatian segera</p>

            <div class="space-y-4">
                <?php if ($bukuTerlambat > 0): ?>
                <div class="flex items-start p-4 bg-red-50 border border-red-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900"><?= $bukuTerlambat ?> Buku Terlambat</h4>
                        <p class="text-xs text-gray-600 mt-1">Ada buku yang belum dikembalikan melewati batas waktu.</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($bukuStokRendah > 0): ?>
                <div class="flex items-start p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900"><?= $bukuStokRendah ?> Buku Stok Rendah</h4>
                        <p class="text-xs text-gray-600 mt-1">Stok buku tinggal sedikit.</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($bukuTerlambat == 0 && $bukuStokRendah == 0): ?>
                <div class="flex items-start p-4 bg-green-50 border border-green-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Semua Berjalan Baik</h4>
                        <p class="text-xs text-gray-600 mt-1">Tidak ada peringatan yang perlu ditangani saat ini.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
