<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RakSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rak';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rak-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rak', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'nama',
            'lokasi',
            [
                'class' => ActionColumn::class,
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

