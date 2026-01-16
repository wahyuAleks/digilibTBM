<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Buku */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Buku', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Cek apakah user adalah admin
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->tipe_user === 'admin';
?>
<div class="buku-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($isAdmin): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->bukuID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->bukuID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bukuID',
            'judul',
            [
                'attribute' => 'kategoriID',
                'label' => 'Kategori',
                'value' => $model->kategori ? $model->kategori->nama : $model->kategoriID,
            ],
            'stok',
        ],
    ]) ?>

</div>

