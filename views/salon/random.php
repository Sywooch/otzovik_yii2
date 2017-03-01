<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$image_dir = Yii::getAlias('@web/files/');
?>

<div class="row" id="random_salons_wrapper">
    <? if (isset($category)) {?>
    <div class="col-xs-12">
        <div class="panel"><div class="panel-body"><h4>Другие салоны <?= $category == 1 ? 'Санкт-Петербурга' : 'Москвы' ?></h4></p></div>
    </div>
        <? } ?>
        <div class="fader"></div>
        <? foreach ($salons as $s) {
    $s->getAllFiles();?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 random-salon" data-id="<?= $s->id ?>">
        <div class="grid card">
            <div class="card-img-wrap">
                <a href="<?= Url::to(['salon/view-alias', 'alias' => $s->alias]) ?>" data-pjax="0">
                    <? if (!empty($s->filename)) { ?>
                    <img class="card-img-top" src="<?= $image_dir . $s->alias . '/' . $s->allfiles[1] ?>" alt="<?= $s->title ?> отзывы">
                    <? } else { ?>
                        <img class="card-img-top" src="/images/nofoto.jpg" alt="<?= $s->title ?> отзывы">
                    <? } ?>
                </a>
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
                    <?= Html::a("Подробнее", Url::to(['salon/view-alias', 'alias' => $s->alias]), [
                        'title' => Yii::t('app', 'View'),
                        'class'=>'btn btn-raised btn-primary btn-sm',
                        'data-pjax' => '0'
                    ]) ?>
                    <?= Html::a("Отзывы  <span class=\"badge\">".$s->getComments()->where(['status' => '1'])->count()."</span>", Url::to(['salon/view-alias', 'alias' => $s->alias]), [
                        'title' => Yii::t('app', 'View'),
                        'class'=>'btn btn-raised btn-warning btn-sm',
                        'data-pjax' => '0'
                    ]) ?>

                </p>
            </div>
        </div>
    </div>
<? }    if (isset($pager)) echo LinkPager::widget([
            'pagination' => $pager,
            'options' => [
                'class' => 'pagination pagination-lg'
            ]
        ]); ?>
</div>