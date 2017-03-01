<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $model app\models\Salon */
/* @var $dataProvider yii\data\ActiveDataProvider */
$image_dir = Yii::getAlias('@web/salon-img/');
$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$salons = $dataProvider->getModels();

?>
<div class="salon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <? if (\Yii::$app->user->can('admin')) echo Html::a('Добавить салон', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php Pjax::begin(['timeout' => 2000]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'alias',
            'description:ntext',
            [
                'attribute' => 'category',
                'content' => function($model, $data) {
                    return $model->getCategoryLabel($data);
                }
            ],
            // 'address',
            // 'phone',
            // 'worktime',
            // 'url:url',
            // 'meta:ntext',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="fa fa-search"></span>Просмотр', $model->alias, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>'btn btn-primary btn-xs',
                            'data-pjax' => '0'
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'update' => \Yii::$app->user->can('admin'),
                    'delete' => \Yii::$app->user->can('admin')
                ]
            ],
        ],
    ]);?>
    <?php Pjax::end(); ?></div>
