<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Profil';
?>

<div class="pt-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Edit Profil</h2>
                <p class="text-sm text-gray-500 mt-1">Update informasi profil Anda</p>
            </div>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-6']]); ?>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        value="<?= Html::encode($user->nama) ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= Html::encode($user->email) ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <p class="text-xs text-gray-500 mt-1">Email akan digunakan untuk login</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe User</label>
                    <input 
                        type="text" 
                        value="<?= Html::encode($user->tipe_user) ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                        disabled
                    >
                    <p class="text-xs text-gray-500 mt-1">Tipe user tidak dapat diubah</p>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="<?= Url::to(['/member/profil']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
