<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
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
        <? if (\Yii::$app->user->can('admin')) echo Html::a('Create Salon', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <div class="row">
        <? foreach ($salons as $s) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="card">
                    <img class="card-img-top" src="<?= $image_dir . $s->filename ?>" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title"><?= $s->title ?></h4>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $s->getCategoryLabel() ?></h6>
                        <p class="card-text"><?= $s->description ?></p>
                        <p class="card-buttons">
                            <?= Html::a("Подробнее", $s->alias, [
                                'title' => Yii::t('app', 'View'),
                                'class'=>'btn btn-raised btn-primary btn-sm',
                            ]) ?>
                            <?= Html::a("Отзывы  <span class=\"badge\">".count($s->comments)."</span>", $s->alias, [
                                'title' => Yii::t('app', 'View'),
                                'class'=>'btn btn-raised btn-warning btn-sm',
                            ]) ?>

                        </p>
                    </div>
                 </div>
            </div>
        <? } ?>
    </div>
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
            'category',
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
                        return Html::a('<span class="fa fa-search"></span>View', $model->alias, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>'btn btn-primary btn-xs',
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
