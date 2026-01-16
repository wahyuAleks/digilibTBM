<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Buku */

$this->title = 'Edit Buku';
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->tipe_user === 'admin';

$kategoriList = ArrayHelper::map(\app\models\Kategori::find()->all(), 'kategoriID', 'nama');
$rakList = ArrayHelper::map(\app\models\Rak::find()->all(), 'id', 'nama');
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
                <h2 class="text-xl font-semibold text-gray-900">Edit Buku</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah informasi buku</p>
            </div>
            <?= Html::a('Kembali', ['index'], [
                'class' => 'px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors'
            ]) ?>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => Url::to(['buku/update', 'id' => $model->bukuID]),
            'method' => 'post',
            'options' => ['class' => 'space-y-4'],
        ]); ?>

        <div class="space-y-4">
            <?= $form->field($model, 'judul', [
                'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                'labelOptions' => ['label' => 'Judul Buku'],
                'inputOptions' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                    'placeholder' => 'Masukkan judul buku'
                ],
            ])->textInput() ?>

            <?= $form->field($model, 'kategoriID', [
                'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                'labelOptions' => ['label' => 'Kategori'],
                'inputOptions' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                ],
            ])->dropDownList($kategoriList, ['prompt' => 'Pilih Kategori']) ?>

            <?php
            // Hanya tampilkan field rakID jika kolomnya ada di database
            try {
                if ($model->hasAttribute('rakID')) {
                    echo $form->field($model, 'rakID', [
                        'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                        'labelOptions' => ['label' => 'Rak'],
                        'inputOptions' => [
                            'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                        ],
                    ])->dropDownList($rakList, ['prompt' => 'Pilih Rak']);
                }
            } catch (\Exception $e) {
                // Skip jika kolom tidak ada
            }
            ?>

            <?= $form->field($model, 'stok', [
                'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                'labelOptions' => ['label' => 'Jumlah Stok'],
                'inputOptions' => [
                    'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                    'type' => 'number',
                    'min' => '0'
                ],
            ])->textInput() ?>
        </div>

        <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
            <div>
                <?= Html::a('Hapus Buku', ['delete', 'id' => $model->bukuID], [
                    'class' => 'px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors',
                    'data' => [
                        'confirm' => 'Apakah Anda yakin ingin menghapus buku "' . Html::encode($model->judul) . '"? Tindakan ini tidak dapat dibatalkan.',
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

        <?php ActiveForm::end(); ?>
    </div>
</div>
