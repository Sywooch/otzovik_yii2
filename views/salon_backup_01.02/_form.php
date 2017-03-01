<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Salon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="salon-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'file')->fileInput()->label('Картинка салона:') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'worktime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="col-md-4">
    <?= $form->field($model, 'meta[title]')->textarea(['rows' => 6])->label('Мета-заголовок') ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'meta[desc]')->textarea(['rows' => 6])->label('Мета-описание') ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'meta[keys]')->textarea(['rows' => 6])->label('Мета-ключи') ?>
    </div>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
