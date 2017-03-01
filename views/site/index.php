<?php

use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'ЛучшийАвтосалон';
?>
</div>
    <div class="img-bg-wrap" data-parallax="scroll" data-image-src="/images/bg.jpg">
        <div class="container">
            <div class="jumbotron">
                <?=Yii::$app->conf->v('html_main_jumbo')?>
                <?=Yii::$app->conf->v('html_main_jumbo_link')?>
            </div>
        </div>
    </div>
<div class="container">
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-12" style="text-align:center">
                <?=Yii::$app->conf->v('main_random_title')?>
            </div>
        </div>
        <div class="main-random" id="random_wrapper">
          <?= $salons ?>
        </div>
        <script>
            intervalId = setInterval(
                function () {window.getRandom();},
                5000);
        </script>

    </div>
</div>
