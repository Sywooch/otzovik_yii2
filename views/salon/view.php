<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\components\CommentsWidget;


/* @var $this yii\web\View */
/* @var $model app\models\Salon */

$admin = \Yii::$app->user->can('admin');
$meta_t = json_decode($model->meta, true)['title'];

$h1_salon_view = strip_tags(Yii::$app->conf->v('h1_salon_view'));
$h1_title = strtr($h1_salon_view, ['{TITLE}' => $model->title]);

$comment_count = $model->getApprovedComments()->count();
$postfix = $comment_count % 10;
$word = $postfix == 1 ? 'отзыв' : ($postfix == 0 || $postfix > 4 ? 'отзывов' : 'отзыва');

$image_dir = Url::base().$image_dir;

$this->title = empty($meta_t) ? $model->title : $meta_t;
$this->params['breadcrumbs'][] = ['label' => '<span itemprop="name">Все автосалоны</span><meta itemprop="position" content="1" />', 'url' => Url::to(['salon/index'], true), 'itemprop' => 'item'];
$this->params['breadcrumbs'][] = ['label' => '<span itemprop="name">'.$model->title.'</span><meta itemprop="position" content="2" />', 'url' => Url::canonical(), 'itemprop' => 'item'];

$category = $model->category;
$coords = json_decode($model->coordinates);
?>
    <div class="salon-view" itemscope itemtype="http://schema.org/LocalBusiness">
        <meta itemprop="name" content="<?= $model->title ?>"/>
        <div class="page-header">
            <h1><?= $h1_title ?></h1>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-3 col-centered">
                <? if (!empty($model->filename)) { ?>
                    <? foreach ($model->allfiles as $f) { ?>
                        <?= Html::a(Html::img($image_dir . '/thumb/' . $f, ['alt' => $model->title, 'class' => 'thumbnail', 'itemprop' => 'image']),
                            $image_dir . '/' . $f,
                            ['class' => 'fancybox',
                                'data-fancybox-group' => 'salon_gal']) ?>
                    <? } ?>
                <? } else { ?>
                    <?= Html::a(Html::img('/images/nofoto.jpg', ['alt' => 'Изображение временно отсутствует', 'class' => 'thumbnail']),
                        '/images/nofoto.jpg',
                        ['class' => 'fancybox',
                            'data-fancybox-group' => 'salon_gal']) ?>
                <? } ?>
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row-content">
                            <div class="star-rating-view">
                                <? if ($model->avgrate != 0) { ?>
                                    <div class="stars" itemprop="aggregateRating" itemscope
                                         itemtype="http://schema.org/AggregateRating">
                                        <meta itemprop="itemReviewed" content="<?= $model->title ?>"/>
                                        <? for ($i = 1; $i <= $model->avgrate; $i++) { ?><span
                                            class="star fullStar"></span><? } ?>
                                        <? for ($j = 1; $j <= (11 - $i); $j++) { ?><span class="star"></span><? } ?>
                                        <meta itemprop="worstRating" content="1">
                                        <span itemprop="ratingValue"><?= $model->avgrate ?>/<span itemprop="bestRating">10</span> (<span
                                                itemprop="reviewCount"><?= $model->getApprovedComments()->count() ?></span> <?= $word ?>
                                            )
                                    </div>
                                <? } else { ?><i>Оценок нет.</i><? } ?>
                            </div>
                            <span class="list-group-item-heading">Контактная информация:</span>

                            <p class="list-group-item-text"><strong>Адрес:</strong> <span itemprop="address" itemscope
                                                                                          itemtype="http://schema.org/PostalAddress"><?= $model->address ?></span>
                            </p>
                            <p class="list-group-item-text"><strong>Сайт:</strong> <span
                                    itemprop="url"><?= $model->url ?></span></p>
                            <p class="list-group-item-text"><strong>Телефон:</strong> <span
                                    itemprop="telephone"><?= $model->phone ?></span></p>
                            <p class="list-group-item-text"><strong>Время работы:</strong> <span
                                    itemprop="openingHours"><?= $model->worktime ?></span></p>
                        </div>
                        <div itemprop="description">
                            <?= $model->description ?>
                        </div>
                        <Br>
                        <i>Поделиться ссылкой:</i>
                        <script type="text/javascript">(function () {
                                if (window.pluso)if (typeof window.pluso.start == "function") return;
                                if (window.ifpluso == undefined) {
                                    window.ifpluso = 1;
                                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                    s.type = 'text/javascript';
                                    s.charset = 'UTF-8';
                                    s.async = true;
                                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                                    var h = d[g]('body')[0];
                                    h.appendChild(s);
                                }
                            })();</script>
                        <div class="pluso" style="display:block" data-background="transparent"
                             data-options="medium,round,line,horizontal,nocounter,theme=06"
                             data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email"
                             data-title="<?= $model->title ?> отзывы"
                             data-description="<?= $model->description ?>"></div>
                    </div>
                </div>
                <?php if (!empty($coords->lat) && !empty($coords->lon)) { ?>
                    <span id="map_toggle" class="btn btn-primary">Показать на карте</span>
                    <div id="map_wrap" style="width:100%; height:200px; display:none;"></div>
                <? } ?>
            </div>
        </div>

        <? if ($admin) { ?>
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот салон?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <? } ?>
        <? if (!empty($comments)) { ?>

            <?php Pjax::begin(['timeout' => 2000, 'id' => 'comments']); ?>

            <?= CommentsWidget::widget([
                'comments' => $comments,
                'admin' => $admin,
                'my_comments' => $my_comments,
                'pager' => $pager
            ]) ?>

            <?php Pjax::end(); ?>
        <? } else { ?><h4>Комментариев нет.</h4><? } ?>

        <div id="new_comment">
            <?= $this->render('@app/views/comment/_form', [
                'model' => new \app\models\Comments,
                'salon_id' => $model->id,
                'embed' => true
            ]) ?>
        </div>
        <?= $this->render('@app/views/salon/random', [
            'salons' => $random,
            'category' => $model->category,
        ]) ?>
    </div>
<?php if (!empty($coords->lat) && !empty($coords->lon)) {
    ?>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        var myMap,
            myPlacemark;

        function init() {
            myMap = new ymaps.Map("map_wrap", {
                center: [<?= $coords->lon . ", " . $coords->lat ?>],
                zoom: 14
            });

            myPlacemark = new ymaps.Placemark([<?= $coords->lon . ", " . $coords->lat ?>], {
                hintContent: '<?= $model->title ?>',
                balloonContent: '<?= $model->address ?>'
            });

            myMap.geoObjects.add(myPlacemark);
        }
    </script>
<?php } ?>