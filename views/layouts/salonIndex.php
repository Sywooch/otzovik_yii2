<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pageNumber string */
/* @var $pageSize string */
/* @var $template string */
$gridClass = '';
$listClass = '';
$view = 'grid';
$this->title = strip_tags(Yii::$app->conf->v('salon_index_title'));
$this->registerMetaTag(['name' => 'description', 'content' => strip_tags(Yii::$app->conf->v('salon_index_desc'))]);
$this->registerMetaTag(['name' => 'keywords', 'content' => strip_tags(Yii::$app->conf->v('salon_index_keys'))]);
$this->params['breadcrumbs'][] = ['label' => '<span itemprop="name">Все автосалоны</span><meta itemprop="position" content="1" />', 'url' => Url::canonical(), 'itemprop' => 'item'];



switch ($category) {
    case 0: $allClass = 'btn-raised'; break;
    case 1: $spbClass = 'btn-raised'; break;
    case 2: $mskClass = 'btn-raised'; break;
}
if ($template == 'indexList') {
    $gridClass = 'btn-raised';
    $view = 'list';
}
else $listClass = 'btn-raised';


$pageSize = $pager->getPageSize();
$pageNumber = $pager->getPage() + 1;
$next = $pager->getLinks(true)['next'];
$prev = $pager->getLinks(true)['prev'];

if ($pageNumber == 1) $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::to(['salon/index'], true)]);
else $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow']);

if ($pageNumber > 1) {
    $this->registerLinkTag(['rel' => 'prev', 'href' => $prev]);
}
if ($pager->getPage() < $dataProvider->getPagination()->getPageCount()) {
    $this->registerLinkTag(['rel' => 'next', 'href' => $next]);
}
?>
<div class="salon-index">

    <h1><?= Html::encode(strip_tags(Yii::$app->conf->v('salon_index_h1'))) ?></h1>

    <p>
        <?= Html::a('Добавить автосалон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['timeout' => 2000, 'enablePushState' => false]); ?>
    <div class="category-buttons"><?= Html::a("Таблица", Url::to([
            'salon/index',
            'category' => 0,
            'page' => $pageNumber,
            'per-page' => $pageSize,
            'view' => 'list']), [
            'title' => Yii::t('app', 'View'),
            'class'=>'btn btn-primary btn-sm left '.$gridClass,
        ]) ?>
        <?= Html::a("Сетка", Url::to(['salon/index',
            'category' => 0,
            'page' => $pageNumber,
            'per-page' => $pageSize,
            'view' => 'grid']), [
            'title' => Yii::t('app', 'View'),
            'class'=>'btn btn-primary btn-sm left '. $listClass,
            'data-push' => 'false'
        ]) ?>
    </div>


    <?= $content ?>

    <?php Pjax::end(); ?>
</div>