<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\captcha\Captcha;
use app\models\Salon;

/* @var $this yii\web\View */
/* @var $model app\models\Comments */
/* @var $form yii\widgets\ActiveForm */

if (!isset($embed)) $embed = false;
?>

<div class="panel panel-primary" id="comment_form_wrapper">
    <div class="panel-heading"><h4><?= $model->isNewRecord ? 'Оставьте свой отзыв!' : 'Редактирование отзыва' ?></h4></div>
    <div class="comments-form row panel-body">
        <?php
        if ($model->isNewRecord) {
            $form = ActiveForm::begin([
                'action' => $model->isNewRecord ? Url::to(['comments/create']) : Url::to(['comments/update']),
                'class' => 'bs-component',
            ]);
            if ($embed == false) echo $form->field($model, 'salon_id')->dropDownList(ArrayHelper::map(Salon::find()->all(), 'id', 'title'))->label('Отзыв об автосалоне:');
            else echo $form->field($model, 'salon_id')->hiddenInput(['value' => $salon_id])->label('');
        }

        else {
            $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
            echo $form->field($model, 'salon_id')->hiddenInput(['value' => $salon_id])->label('');
        }
        ?>
        <div class="col-sm-12 col-md-6">
            <?= $form->field($model, 'author', ['options' => ['class' => 'form-group label-floating']])->textInput(['maxlength' => true])?>

            <div class="star_wrap">
            <?= $form->field($model, 'rate')->radioList(
               ['1'=>'1', '2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'],
                [
                    'tag' => 'div',
                    'item' => function ($index, $label, $name, $checked, $value) {
                    return  '<input name="CommentsRate" type="radio" '.$checked.' value="'.$value.'" id="st'.$value.'" class="rating">';
                },
                'separator' => false]) ?>

            </div>
            <?= $form->field($model, 'captcha')->widget(Captcha::className()) ?>
        </div>
        <div class="col-sm-12 col-md-6">
            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'car', ['options' => ['class' => 'form-group label-floating']])->textInput(['maxlength' => 50])?>

            <? if (\Yii::$app->user->can('admin')) echo $form->field($model, 'created_at', ['options' => ['class' => 'form-group label-floating', 'id' => 'datetimepicker']])->textInput(); ?>


            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Оставить отзыв' : 'Изменить отзыв', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>



        <?php ActiveForm::end(); ?>

    </div>
</div>