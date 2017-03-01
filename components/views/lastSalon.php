<?
use yii\helpers\Url;

?>

<div class="bs-component last-added">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title">
                <a data-toggle="collapse" href="#collapse1">Последние добавленные автосалоны</a>
            </span>

        </div>
        <div class="panel-collapse collapse" id="collapse1">
            <? foreach ($salons as $s) { ?>
                <div class="panel-body">
                    <a href="<?= Url::to(['salon/view-alias', 'alias' => $s->alias]) ?>"><?= $s->title ?></a>
                </div>
            <? } ?>
        </div>
    </div>
</div>