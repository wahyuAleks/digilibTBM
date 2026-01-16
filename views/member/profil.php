<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Profil Anggota';
?>

<div class="pt-6">
    <div class="max-w-4xl mx-auto">
        
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Profil Anggota</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola informasi profil Anda</p>
                </div>
                <a href="<?= Url::to(['/member/update-profil']) ?>" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">
                    Edit Profil
                </a>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-4 pb-6 border-b border-gray-200">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800"><?= Html::encode($user->nama ?? 'Nama Anggota') ?></h3>
                        <p class="text-sm text-gray-500">ID User: <?= Html::encode($user->userid ?? 'N/A') ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-gray-900"><?= Html::encode($user->nama ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Username</label>
                        <p class="mt-1 text-gray-900"><?= Html::encode($user->email ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tipe User</label>
                        <p class="mt-1 text-gray-900"><?= Html::encode($user->tipe_user ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                        <p class="mt-1 text-gray-900">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

