<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$image_dir = Yii::getAlias('@web/files/');
$this->title = $title;
$salons = $dataProvider->getModels();
?>
    <?php $this->beginContent('@app/views/layouts/salonIndex.php', [
        'pager' => $pager,
        'dataProvider' => $dataProvider,
        'template' => $template,
        'category' => $category,
    ]); ?>

    <div class="row">
        <? foreach ($salons as $s) {
            $rate = round($s->avgrate / 2, 1);
            $s->getAllFiles();
            ?>
            <div class="col-xs-12 list">
                <div class="card">
                    <div class="card-img-wrap col-md-3">

                        <a href="<?= Url::to(['salon/view-alias', 'alias' => $s->alias]) ?>" data-pjax="0">
                            <img class="card-img-top" width="250" src="<?= $image_dir . $s->alias . '/thumb/' . $s->allfiles[1] ?>" alt="<?= $s->title ?> отзывы">
                        </a>
                        <? if ($s->avgrate != 0) { ?>
                        <div class="btn-group btn-rate">
                            <span class="btn btn-raised btn-danger btn-xs">
                                <?= $rate ?> <? for ($i=1;$i<=round($rate);$i++) { ?><i class="material-icons">grade</i><?}?>
                            </span>
                        </div>
                        <? } ?>
                    </div>
                    <div class="card-block col-md-9">
                        <h4 class="card-title"><?= $s->title ?></h4>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $s->getCategoryLabel() ?></h6>
                        <p class="card-text"><?= mb_substr($s->description, 0, 200) ?>...</p>
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
        <? }
        echo LinkPager::widget([
            'pagination' => $pager,
            'options' => [
                'class' => 'pagination pagination-lg'
            ]
        ]); ?>
    </div>

<?php $this->endContent(); ?>
