<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Salon */

$this->title = $model->title;
echo $model->getCategoryLabel();
$this->params['breadcrumbs'][] = ['label' => 'Все автосалоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salon-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::img($image_dir.'/thumb/'.$model->filename, ['alt' => $model->title]) ?></p>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'title',
            'alias',
            'description:ntext',
            'category',
            'address',
            'phone',
            'worktime',
            'url:url',
            'meta:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <? if (!empty($comments)) foreach($comments as $comment) { ?>

        <?= DetailView::widget([
        'model' => $comment,
        'attributes' => [
            'id',
            'salon_id',
            'author',
            'text:ntext',
            'rate',
            'status',
            'created_at',
        ],
    ]) ?>

   <? } else { ?><h4>Комментариев нет.</h4><? } ?>
    <?= $this->render('@app/views/comment/_form', [
        'model' => new \app\models\Comments,
        'salon_id' => $model->id,
    ]) ?>
</div>
