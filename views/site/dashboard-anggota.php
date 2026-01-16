<?php
/* @var $this yii\web\View */
/* @var $totalPeminjaman int */
/* @var $peminjamanAktif int */
/* @var $peminjamanSelesai int */
/* @var $peminjamanTerlambat int */
/* @var $menungguVerifikasi int */
/* @var $totalDenda float */
/* @var $peminjamanTerbaru app\models\Peminjaman[] */
/* @var $anggota app\models\Anggota */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Anggota;
use app\models\Peminjaman;

$this->title = 'Dashboard Anggota';

$user = Yii::$app->user->identity;

// Ambil nama anggota
$namaAnggota = 'Anggota';
if ($anggota && $anggota->user) {
    $namaAnggota = $anggota->user->nama ?? $anggota->user->email ?? 'Anggota';
} elseif ($user) {
    $namaAnggota = $user->nama ?? $user->email ?? 'Anggota';
}
?>

<div class="pt-6">
    <!-- Flash Messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        <?= Yii::$app->session->getFlash('success') ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        <?= Yii::$app->session->getFlash('warning') ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Welcome Banner Section -->
    <div class="mb-8 relative overflow-hidden rounded-2xl shadow-lg">
        <div class="relative h-64 bg-gradient-to-r from-blue-600 to-purple-600">
            <!-- Background Image -->
            <img src="<?= Yii::$app->request->baseUrl ?>/images/library_welcome_banner.png" 
                 alt="Library Banner" 
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40">
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-purple-900/80"></div>
            
            <!-- Content -->
            <div class="relative h-full flex flex-col justify-center px-8">
                <h1 class="text-4xl font-bold text-white mb-3">
                    Selamat Datang, <?= Html::encode($namaAnggota) ?>! 👋
                </h1>
                <p class="text-blue-100 text-lg mb-4">
                    Jelajahi koleksi digital kami dan temukan buku favorit Anda
                </p>
                <div class="flex gap-3">
                    <a href="<?= Url::to(['/member/index']) ?>" 
                       class="px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari Buku
                    </a>
                    <a href="<?= Url::to(['/member/peminjaman']) ?>" 
                       class="px-6 py-3 bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Peminjaman Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Info & Location Section -->
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Library Location -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lokasi Perpustakaan
                </h3>
            </div>
            <div class="flex items-center justify-center h-80 bg-gradient-to-br from-blue-50 to-purple-50">
                <div class="text-center p-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Perpustakaan Digital TBM</h4>
                    <p class="text-gray-600 mb-2">Jl. Perpustakaan No. 123</p>
                    <p class="text-gray-600 mb-4">Jakarta Pusat, DKI Jakarta 10110</p>
                    <a href="https://maps.google.com/?q=-6.2088,106.8456" target="_blank" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Kontak
                </h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Alamat</p>
                        <p class="text-sm text-gray-600 mt-1">Jl. Perpustakaan No. 123<br>Jakarta Pusat, DKI Jakarta 10110</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Telepon</p>
                        <p class="text-sm text-gray-600 mt-1">(021) 1234-5678</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Email</p>
                        <p class="text-sm text-gray-600 mt-1">info@digilibtbm.com</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Jam Operasional</p>
                        <p class="text-sm text-gray-600 mt-1">
                            Senin - Jumat: 08:00 - 17:00<br>
                            Sabtu: 09:00 - 15:00<br>
                            Minggu: Tutup
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Ringkasan Peminjaman Anda</h2>
    </div>

    <?php if (!$anggota): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        Data anggota tidak ditemukan. Silakan hubungi administrator untuk mengaktifkan akun Anda.
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>

    <!-- Notifikasi Menunggu Verifikasi -->
    <?php if ($menungguVerifikasi > 0): ?>
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
                <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="text-sm font-medium text-yellow-800 hover:text-yellow-900 underline">
                    Lihat Detail →
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Sedang Dipinjam</span>
                <div class="text-blue-500 bg-blue-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $peminjamanAktif ?></span>
                <p class="text-xs text-gray-400 mt-1">
                    <?php if ($menungguVerifikasi > 0): ?>
                        <span class="text-yellow-600 font-medium"><?= $menungguVerifikasi ?> menunggu verifikasi</span>
                    <?php else: ?>
                        peminjaman aktif
                    <?php endif; ?>
                </p>
            </div>
        </a>

        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Terlambat</span>
                <div class="text-red-500 bg-red-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $peminjamanTerlambat ?></span>
                <p class="text-xs text-gray-400 mt-1">perlu dikembalikan</p>
            </div>
        </a>

        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'riwayat']) ?>" class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-600">Selesai</span>
                <div class="text-green-500 bg-green-50 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800"><?= $peminjamanSelesai ?></span>
                <p class="text-xs text-gray-400 mt-1">peminjaman selesai</p>
            </div>
        </a>

        <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col justify-between h-32 shadow-sm">
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
                <p class="text-xs text-gray-400 mt-1"><?= $peminjamanTerlambat ?> buku terlambat</p>
            </div>
        </div>

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
                <a href="<?= Url::to(['/member/peminjaman']) ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Semua →
                </a>
            </div>

            <div class="space-y-4">
                <?php if (empty($peminjamanTerbaru)): ?>
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada peminjaman</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($peminjamanTerbaru as $peminjaman): ?>
                        <?php
                        // Ambil judul buku dari item peminjaman
                        $judulBuku = 'N/A';
                        try {
                            if (isset($peminjaman->itemPeminjamans) && !empty($peminjaman->itemPeminjamans)) {
                                $judulBukuList = [];
                                foreach ($peminjaman->itemPeminjamans as $item) {
                                    if ($item && $item->buku) {
                                        $judulBukuList[] = $item->buku->judul;
                                    }
                                }
                                $judulBuku = !empty($judulBukuList) ? implode(', ', $judulBukuList) : 'N/A';
                            }
                        } catch (\Exception $e) {
                            // Skip jika error
                        }
                        
                        // Ambil tanggal pinjam atau tanggal dibuat
                        $tanggalPinjam = '-';
                        if ($peminjaman->hasAttribute('tanggalPinjam') && $peminjaman->tanggalPinjam) {
                            $tanggalPinjam = date('d M Y', strtotime($peminjaman->tanggalPinjam));
                        } elseif ($peminjaman->hasAttribute('tglPinjam') && $peminjaman->tglPinjam) {
                            $tanggalPinjam = date('d M Y', strtotime($peminjaman->tglPinjam));
                        } else {
                            // Jika belum ada tanggal pinjam, gunakan tanggal sekarang untuk yang menunggu verifikasi
                            if ($peminjaman->status === 'menunggu_verifikasi_admin') {
                                $tanggalPinjam = date('d M Y');
                            }
                        }
                        
                        // Ambil jatuh tempo jika ada
                        $jatuhTempo = '';
                        if ($peminjaman->tglJatuhTempo) {
                            $jatuhTempo = ' | Jatuh Tempo: ' . date('d M Y', strtotime($peminjaman->tglJatuhTempo));
                        }
                        
                        // Status dengan informasi yang lebih jelas
                        $statusText = $peminjaman->status ?? 'N/A';
                        $statusClass = 'bg-gray-100 text-gray-800';
                        $statusIcon = '';
                        if ($statusText === 'dipinjam') {
                            $statusClass = 'bg-blue-100 text-blue-800';
                            $statusText = 'Dipinjam';
                            $statusIcon = '✓';
                        } elseif ($statusText === 'menunggu_verifikasi_admin') {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Menunggu Verifikasi';
                            $statusIcon = '⏳';
                        } elseif ($statusText === 'dikembalikan') {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Dikembalikan';
                            $statusIcon = '✓';
                        }
                        ?>
                        <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm mb-1"><?= Html::encode($judulBuku) ?></h4>
                                    <p class="text-xs text-gray-500">
                                        <?= $tanggalPinjam ?>
                                        <?= $jatuhTempo ?>
                                    </p>
                                    <?php if ($statusText === 'Menunggu Verifikasi'): ?>
                                        <p class="text-xs text-yellow-600 mt-1 italic">
                                            Admin akan memverifikasi peminjaman Anda segera
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium <?= $statusClass ?> ml-3 whitespace-nowrap">
                                    <?= $statusIcon ?> <?= $statusText ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informasi & Peringatan -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Informasi & Peringatan</h3>
                <p class="text-sm text-gray-500">Status dan notifikasi penting</p>
            </div>

            <div class="space-y-4">
                <?php if ($peminjamanTerlambat > 0): ?>
                <div class="flex items-start p-4 bg-red-50 border border-red-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900"><?= $peminjamanTerlambat ?> Buku Terlambat</h4>
                        <p class="text-xs text-gray-600 mt-1">Segera kembalikan buku yang sudah melewati batas waktu peminjaman.</p>
                        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="text-xs text-red-600 hover:text-red-800 font-medium mt-2 inline-block">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($menungguVerifikasi > 0): ?>
                <div class="flex items-start p-4 bg-yellow-50 border border-yellow-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900"><?= $menungguVerifikasi ?> Peminjaman Menunggu Verifikasi</h4>
                        <p class="text-xs text-gray-600 mt-1">Admin akan memverifikasi peminjaman Anda segera.</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($totalDenda > 0): ?>
                <div class="flex items-start p-4 bg-orange-50 border border-orange-100 rounded-xl">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Total Denda: Rp <?= number_format($totalDenda, 0, ',', '.') ?></h4>
                        <p class="text-xs text-gray-600 mt-1">Anda memiliki denda yang harus dibayar. Silakan hubungi admin untuk pembayaran.</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($peminjamanTerlambat == 0 && $menungguVerifikasi == 0 && $totalDenda == 0): ?>
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

                <!-- Informasi Anggota -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Informasi Anggota</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nama</p>
                            <p class="text-sm font-medium text-gray-900"><?= Html::encode($namaAnggota) ?></p>
                        </div>
                        
                        <?php if ($anggota->user && isset($anggota->user->email)): ?>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-900"><?= Html::encode($anggota->user->email) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <p class="text-xs text-gray-500 mb-1">ID Anggota</p>
                            <p class="text-sm font-medium text-gray-900"><?= Html::encode($anggota->anggotaID) ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= Url::to(['/member/profil']) ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Edit Profil →
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?= Url::to(['/member/index']) ?>" class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari Buku
            </a>
            <a href="<?= Url::to(['/member/peminjaman']) ?>" class="flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Riwayat Peminjaman
            </a>
            <a href="<?= Url::to(['/member/profil']) ?>" class="flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil Saya
            </a>
        </div>
    </div>

    <?php endif; ?>
</div>

