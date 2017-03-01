<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Salon */
/* @var $form yii\widgets\ActiveForm */

$admin = Yii::$app->user->can('admin');
$image_dir = Yii::getAlias('@web/files/'.$model->alias);
$model->meta = json_decode($model->meta, true);
$model->coordinates = json_decode($model->coordinates, true);
$model->getAllFiles();
?>

<div class="salon-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true])->hint('Alias - это идентификатор автосалона в URL. Например, если alias — «avtomir», то этот автосалон будет доступен по URL http://site.ru/salon/avtomir.') ?>

    <?= $form->field($model, 'filename')->hiddenInput(['value' => implode(' ', $model->allfiles)])->label('') ?>
    
    <?= $form->field($model, 'file[]')->fileInput(['multiple' => true])->label('Картинка салона (можно выбрать до четырёх):') ?>
 
    <? if (!$model->isNewRecord) { $i = 1;?>
        <ul id="sortable_img">
        <? foreach ($model->allfiles as $f) { ?>
            <li class="ui-state-default" data-file="<?= $f ?>">
            <?= Html::a(Html::img($image_dir.'/thumb/'.$f, ['alt' => $model->title, 'class' => 'thumbnail']),
                $image_dir.'/'.$f,
                ['class' => 'fancybox inline',
                    'data-fancybox-group' => 'salon_gal']) ?>
        <? } ?>
            </li>
        </ul>
    <? } ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
         'preset' => 'full',
    ]) ?>

    <?= $form->field($model, 'category')->dropDownList($model->categoryLabels()) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'data-action' => Url::to(['salon/search-address'])])->hint('Введите адрес (вместе с городом), и система попробует подобрать координаты для Яндекс.Карт. Если координаты ниже заполнены, то подбирать автоматически система не будет.') ?>

    <?= $form->field($model, 'coordinates[lat]')->textInput(['maxlength' => true, 'class' => 'form-control form-inline']) ?>
    <?= $form->field($model, 'coordinates[lon]')->textInput(['maxlength' => true, 'class' => 'form-control form-inline']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'worktime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <? if ($admin) { ?>

    <div class="meta row">
        <div class="col-md-4">
        <?= $form->field($model, 'meta[title]')->textarea(['rows' => 6])->label('Мета-заголовок') ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'meta[desc]')->textarea(['rows' => 6])->label('Мета-описание') ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'meta[keys]')->textarea(['rows' => 6])->label('Мета-ключи') ?>
        </div>
    </div>
    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'avgrate')->textInput(['maxlength' => 3]) ?>

    <? } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
