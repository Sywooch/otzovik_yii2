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
                    <div class="card-img-wrap">
                        <img class="card-img-top" src="<?= $image_dir . $s->filename ?>" alt="Card image cap">
                        <? if ($s->avgrate != 0) { ?>
                        <div class="btn-group btn-rate">
                            <span class="btn btn-raised btn-danger btn-xs">
                                <?= $s->avgrate ?> <i class="material-icons">grade</i>
                            </span>
                        </div>
                        <? } ?>
                    </div>
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
</div>
