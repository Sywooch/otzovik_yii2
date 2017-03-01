<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SiteConfig */

$this->title = 'Create Site Config';
$this->params['breadcrumbs'][] = ['label' => 'Site Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
