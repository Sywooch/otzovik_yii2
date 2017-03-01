<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\components\LastSalonWidget;
use app\components\LastCommentsWidget;
use app\components\SideMenuWidget;

AppAsset::register($this);

$this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);
$this->registerLinkTag([
    'rel' => 'stylesheed',
    'href' => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
    'integrity' => "sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN",
    'crossorigin' => 'anonymous'
]);
$currentRoute = Yii::$app->controller->getRoute();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="application/ld+json">
    <?= $this->params['jsonld'] ?>
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'ЛучшийАвтосалон',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/']],
            ['label' => 'Салоны', 'url' => ['/salon/index']],
            ['label' => 'О нас', 'url' => ['/site/about']],
            ['label' => 'Контакты', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? '' : (
                '<li>' .
                Html::a("Отзывы", Url::to(['comments/index'])) .
                '</li>' .
                '<li>' .
                Html::a("Салоны", Url::to(['salon/admin-view'])) .
                '</li>' .
                '<li>' .
                Html::a("Настройки", Url::to(['config/index'])) .
                '</li>' .
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    ?>
    <form class="navbar-form navbar-left" id="form_head-search" action="<?= Url::to(['salon/search-ajax']) ?>" method="post">
        <div class="form-group">
            <input type="text" class="form-control col-md-8" placeholder="Поиск" id="head-search" onkeyup="window.headSearch(this.value)">
            <div id="head-search-results" class="dropdown-search-result"></div>
        </div>
    </form>
    <?
    NavBar::end();
    ?>

    <div class="container">
        <? if ($currentRoute != 'site/index') { ?>
        <div class="col-sm-12 col-md-4 col-lg-3">
            <?= SideMenuWidget::widget() ?>
            <?= LastSalonWidget::widget() ?>
            <?= LastCommentsWidget::widget() ?>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-9">
            <? } ?>
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => '<span itemprop="name">Главная</span><meta itemprop="position" content="0" />', 'url' => Url::home(), 'itemprop' => 'item'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'itemTemplate' => '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">{link}</li>',
                'encodeLabels' => false,
                'options' => ['itemscope' => '', 'itemtype' => 'http://schema.org/BreadcrumbList', 'class' => 'breadcrumb']
            ]) ?>
            <?= $content ?>

            <? if ($currentRoute != 'site/index') { ?></div><? } ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ЛучшийАвтосалон <?= date('Y') ?></p>
        <p class="pull-right">По всем вопросам: feedback@лучшийавтосалон.рф</p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
