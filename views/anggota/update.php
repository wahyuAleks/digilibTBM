<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Anggota */

$this->title = 'Edit Anggota';
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->tipe_user === 'admin';

$userList = ArrayHelper::map(\app\models\User::find()->where(['tipe_user' => 'anggota'])->all(), 'userid', 'email');
?>

<?php if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
        <?= \Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
        <?= \Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<div class="p-6 max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit Anggota</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah informasi anggota</p>
            </div>
            <?= Html::a('Kembali', ['index'], [
                'class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors'
            ]) ?>
        </div>

        <?php
        // Ambil data user jika ada
        $user = null;
        if ($model->user) {
            $user = $model->user;
        } elseif ($model->anggotaID) {
            // Coba cari user berdasarkan anggotaID (jika relasi menggunakan anggotaID = userid)
            $user = \app\models\User::findOne($model->anggotaID);
        }
        ?>

        <form action="<?= Url::to(['anggota/update', 'id' => $model->anggotaID]) ?>" method="post" class="space-y-4">
            <?= Html::hiddenInput(\Yii::$app->request->csrfParam, \Yii::$app->request->csrfToken) ?>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= Html::encode($user ? $user->nama : '') ?>" placeholder="Masukkan nama lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= Html::encode($user ? $user->email : '') ?>" placeholder="email@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Email akan digunakan untuk login</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="telepon" value="<?= Html::encode($user && isset($user->telepon) ? $user->telepon : '') ?>" placeholder="08123456789" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"><?= Html::encode($user && isset($user->alamat) ? $user->alamat : '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User</label>
                    <select name="Anggota[userID]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih User</option>
                        <?php foreach ($userList as $userId => $userEmail): ?>
                            <option value="<?= $userId ?>" <?= ($model->anggotaID == $userId) ? 'selected' : '' ?>>
                                <?= Html::encode($userEmail) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih user yang terkait dengan anggota ini</p>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                <div>
                    <?php
                    // Ambil nama anggota untuk konfirmasi
                    $namaAnggota = 'anggota ini';
                    if ($user && isset($user->nama)) {
                        $namaAnggota = Html::encode($user->nama);
                    } elseif ($user && isset($user->email)) {
                        $namaAnggota = Html::encode($user->email);
                    }
                    ?>
                    <?= Html::a('Hapus Anggota', ['delete', 'id' => $model->anggotaID], [
                        'class' => 'px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors',
                        'data' => [
                            'confirm' => 'Apakah Anda yakin ingin menghapus anggota "' . $namaAnggota . '"? Tindakan ini tidak dapat dibatalkan.',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                <div class="flex space-x-3">
                    <?= Html::a('Batal', ['index'], [
                        'class' => 'px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors'
                    ]) ?>
                    <?= Html::submitButton('Simpan Perubahan', [
                        'class' => 'px-6 py-2 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition-colors'
                    ]) ?>
                </div>
            </div>
        </form>
    </div>
</div>
