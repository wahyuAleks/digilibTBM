<?php

use app\models\Kategori;
use app\models\Rak;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Buku */
/* @var $form yii\widgets\ActiveForm */

$kategoriList = ArrayHelper::map(Kategori::find()->all(), 'kategoriID', 'nama');
$rakList = ArrayHelper::map(Rak::find()->all(), 'id', 'nama');
?>

<div class="buku-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kategoriID')->dropDownList($kategoriList, ['prompt' => 'Pilih Kategori']) ?>

    <?= $form->field($model, 'rakID')->dropDownList($rakList, ['prompt' => 'Pilih Rak']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

