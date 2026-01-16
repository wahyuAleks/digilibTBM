<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Kategori */

$this->title = 'Update Kategori: ' . $model->kategoriID;
$this->params['breadcrumbs'][] = ['label' => 'Kategori', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kategoriID, 'url' => ['view', 'id' => $model->kategoriID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

