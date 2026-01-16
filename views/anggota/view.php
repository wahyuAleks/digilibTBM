<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Anggota */

$this->title = $model->user->nama ?? 'Detail Anggota';
$this->params['breadcrumbs'][] = ['label' => 'Anggota', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anggota-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->anggotaID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->anggotaID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'anggotaID',
            [
                'attribute' => 'nama',
                'value' => $model->user->nama ?? '-',
                'label' => 'Nama',
            ],
            [
                'attribute' => 'email',
                'value' => $model->user->email ?? '-',
                'label' => 'Email',
            ],
            [
                'attribute' => 'username',
                'value' => $model->user->username ?? '-',
                'label' => 'Username',
            ],
            [
                'attribute' => 'tipe_user',
                'value' => $model->user->tipe_user ?? '-',
                'label' => 'Tipe User',
            ],
        ],
    ]) ?>

</div>

