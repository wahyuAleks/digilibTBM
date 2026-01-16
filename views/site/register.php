<?php

/** @var yii\web\View $this */
/** @var app\models\RegistrasiForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'TBM Digital - Registrasi';
$this->params['breadcrumbs'] = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <!-- Catatan: Di proyek nyata, ini harus dimuat via Yii2 AppAsset.php -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        /* Memperbaiki tampilan input agar icon dan teks input rata */
        .input-group input {
            background-color: transparent !important;
            border: none;
            padding: 0;
            margin-left: 0.75rem; /* Tambahkan sedikit jarak */
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="flex max-w-5xl w-full bg-white rounded-xl overflow-hidden shadow-2xl">
        <!-- Left Side - Information -->
        <div class="w-full lg:w-1/2 p-10 space-y-8 bg-blue-50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.405 9.497 5 8 5c-3.313 0-6 2.687-6 6v3c0 2.21 1.79 4 4 4h14c2.21 0 4-1.79 4-4v-3c0-3.313-2.687-6-6-6-1.497 0-2.832.405-4 1.253z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">TBM Digital</h1>
                    <p class="text-sm text-blue-600">Sistem Perpustakaan Modern</p>
                </div>
            </div>
            <div class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-800">Kelola Perpustakaan dengan Mudah</h2>
                <p class="text-gray-600">
                    Platform manajemen perpustakaan yang lengkap untuk administrasi, peminjaman buku, dan pengelolaan anggota secara digital.
                </p>
                
                <div class="bg-white p-4 rounded-lg card-shadow">
                    <div class="flex items-start space-x-3">
                        <div class="text-blue-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2m-14 4V9a2 2 0 012-2m7 3h.01M7 11h.01M9 11h.01"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">Katalog Digital</p>
                            <p class="text-sm text-gray-500">Cari dan pinjam buku secara online</p>
                        </div>
                    </div>
                </div>
                <!-- Perbaikan lokasi div yang salah di sidebar kiri -->
                <div class="bg-white p-4 rounded-lg card-shadow">
                    <div class="flex items-start space-x-3">
                        <div class="text-indigo-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-1a3 3 0 01-3-3v-3h-2v3a3 3 0 01-3 3H7M4 8h16M4 12h16m-7-5v5m-2-5v5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">Manajemen Anggota</p>
                            <p class="text-sm text-gray-500">Kelola data anggota dengan efisien</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 p-10 space-y-6">
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800">Selamat Datang</h2>
                <p class="text-sm text-gray-500">Login atau daftar untuk melanjutkan</p>
                
                <!-- PERBAIKAN UTAMA: TOGGLE LOGIN/REGISTRASI -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <!-- Login (Non-Aktif): Menambahkan font-semibold dan bg-transparent -->
                    <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="flex-1 py-2 text-center text-gray-700 font-semibold bg-transparent rounded-lg transition duration-150">Login</a>
                    <!-- Registrasi (Aktif) -->
                    <button class="flex-1 py-2 text-center text-gray-700 bg-white shadow-md rounded-lg transition duration-150 font-semibold" disabled>Registrasi</button>
                </div>
            </div>

            <?php
            $form = ActiveForm::begin([
                'id' => 'register-form',
                'options' => ['class' => 'space-y-5'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'text-sm font-medium text-gray-700 block mb-1'],
                    'errorOptions' => ['class' => 'error-message'],
                ],
            ]);
            ?>

            <?= $form->field($model, 'nama', [
                'template' => '<div>
                    <label for="nama" class="text-sm font-medium text-gray-700 block mb-1">Nama Lengkap</label>
                    <div class="relative flex items-center bg-gray-100 rounded-lg p-3 input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {input}
                    </div>
                    {error}
                </div>',
                'inputOptions' => [
                    'id' => 'nama',
                    'placeholder' => 'Masukkan nama lengkap',
                    'autofocus' => true,
                    'class' => 'bg-transparent w-full focus:outline-none text-gray-800',
                ],
            ])->textInput() ?>

            <?= $form->field($model, 'email', [
                'template' => '<div>
                    <label for="email" class="text-sm font-medium text-gray-700 block mb-1">Email</label>
                    <div class="relative flex items-center bg-gray-100 rounded-lg p-3 input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v-2a4 4 0 00-4-4v0"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7 7m0 0l-7 7m7-7h14a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v0"/>
                        </svg>
                        {input}
                    </div>
                    {error}
                </div>',
                'inputOptions' => [
                    'id' => 'email',
                    'placeholder' => 'email@example.com',
                    'class' => 'bg-transparent w-full focus:outline-none text-gray-800',
                ],
            ])->textInput(['type' => 'email']) ?>

            <?= $form->field($model, 'username', [
                'template' => '<div>
                    <label for="username" class="text-sm font-medium text-gray-700 block mb-1">Username</label>
                    <div class="relative flex items-center bg-gray-100 rounded-lg p-3 input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {input}
                    </div>
                    {error}
                </div>',
                'inputOptions' => [
                    'id' => 'username',
                    'placeholder' => 'Masukkan username',
                    'class' => 'bg-transparent w-full focus:outline-none text-gray-800',
                ],
            ])->textInput() ?>

            <?= $form->field($model, 'password', [
                'template' => '<div>
                    <label for="password" class="text-sm font-medium text-gray-700 block mb-1">Password</label>
                    <div class="relative flex items-center bg-gray-100 rounded-lg p-3 input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        {input}
                    </div>
                    {error}
                </div>',
                'inputOptions' => [
                    'id' => 'password',
                    'placeholder' => 'Masukkan password',
                    'class' => 'bg-transparent w-full focus:outline-none text-gray-800',
                ],
            ])->passwordInput() ?>

            <?= $form->field($model, 'password_repeat', [
                'template' => '<div>
                    <label for="password_repeat" class="text-sm font-medium text-gray-700 block mb-1">Ulangi Password</label>
                    <div class="relative flex items-center bg-gray-100 rounded-lg p-3 input-group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        {input}
                    </div>
                    {error}
                </div>',
                'inputOptions' => [
                    'id' => 'password_repeat',
                    'placeholder' => 'Ulangi password',
                    'class' => 'bg-transparent w-full focus:outline-none text-gray-800',
                ],
            ])->passwordInput() ?>

            <?= Html::submitButton('Daftar Sebagai Anggota', [
                'class' => 'w-full bg-black text-white py-3 rounded-lg font-semibold transition duration-150 hover:bg-gray-800',
                'name' => 'register-button'
            ]) ?>

            <?php ActiveForm::end(); ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>

            <div class="text-center space-y-3">
                <p class="text-sm text-gray-600">Sudah punya akun? <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="text-blue-600 hover:underline">Login di sini</a></p>
                <div class="border-t border-gray-200 pt-3">
                    <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Lihat Katalog Buku
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>