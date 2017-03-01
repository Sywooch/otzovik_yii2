<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $title string */
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


<?= $this->render('@app/views/salon/random', [
    'salons' => $salons,
    'pager' => $pager
]) ?>

    <?php $this->endContent(); ?>
