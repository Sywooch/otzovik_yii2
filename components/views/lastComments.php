<?
use yii\helpers\Url;

?>

<div class="bs-component last-added">
    <div class="panel panel-success">
        <div class="panel-heading">
            <span class="panel-title">
                <a data-toggle="collapse" href="#collapse2">Последние отзывы</i></a>
            </span>
        </div>
        <div class="panel-collapse collapse" id="collapse2">
            <? foreach ($comments as $c) { ?>
                <div class="panel-body">
                    <a href="<?= Url::to(['salon/view-alias', 'alias' => $c->salon->alias]) ?>">
                        <?= $c->salon->title ?>
                    </a>
                    <p><?= $c->author ?>: <i><?= mb_substr($c->text, 0, 150) ?>...</i></p>
                </div>
            <? } ?>
        </div>
    </div>
</div>