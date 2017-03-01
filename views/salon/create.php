<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Salon */

$this->title = 'Добавить автосалон';
$this->params['breadcrumbs'][] = ['label' => 'Все автосалоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
