<?
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="bs-component side-menu">
    <ul class="list-group">
        <li class="list-group-item">
            <span class="badge"><?=$all?></span>
            <?= Html::a("Все автосалоны", Url::to(['salon/index'])) ?>
        </li>
        <li class="list-group-item">
            <span class="badge"><?=$spb?></span>
            <?= Html::a("Автосалоны Петербурга", Url::to(['salon/spb'])) ?>
        </li>
        <li class="list-group-item">
            <span class="badge"><?=$msk?></span>
            <?= Html::a("Автосалоны Москвы", Url::to(['salon/msk'])) ?>
        </li>
    </ul>
</div>