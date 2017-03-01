<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div class="list-group comments-section"> <?foreach($comments as $comment) { ?>
        <? if ($comment->status == 0) $classname = 'not-approved';
        else $classname = '';?>
        <div class="list-group-item <?=$classname?>" itemprop="review" itemscope itemtype="http://schema.org/Review">
            <meta itemprop="itemReviewed" content="<?=$model->title?>" />
            <div class="row-action-primary" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                <i class="material-icons">
                    <? if ($comment->rate == 0) { ?>
                        sentiment_neutral
                    <? }  elseif ($comment->rate <= 2) {?>
                        sentiment_very_dissatisfied
                    <?} elseif ($comment->rate <= 4) {?>
                        sentiment_dissatisfied
                    <?} elseif ($comment->rate <= 6) {?>
                        sentiment_neutral
                    <?} elseif ($comment->rate <= 8) {?>
                        sentiment_satisfied
                    <?} elseif ($comment->rate <= 10) {?>
                        sentiment_very_satisfied
                    <? } ?>
                </i>
                <span itemprop="worstRating" content = "1"></span>
                <span class="rating" itemprop="ratingValue"><? if (empty($comment->rate)) echo '—'; else echo $comment->rate ?>/10</span>
                <span itemprop="bestRating" content = "10"></span>
            </div>
            <div class="row-content">
                <span class="list-group-item-heading"><span itemprop="author"><?= $comment->author ?></span>: <i><? if(in_array($comment->id, $my_comments)) echo 'Мой отзыв' ?></i></span>
                <p class="list-group-item-text"><span itemprop="description"><?= $comment->text ?></span></p>
                <? if ($comment->car) { ?>
                    <p class="list-group-item-text"><strong>Приобретённый автомобиль:</strong> <?= $comment->car ?></p>
                <? } ?>
                <?  if ($admin){
                    $url = \yii\helpers\Url::toRoute([
                        'comments/approve',
                        'id' => $comment->id,
                        'approved' => 1,
                    ]);
                    ?>

                    <p class="approve-status"><?= $comment->status == 1 ? 'Одобрено' : 'На модерации <a href="'.$url.'">Одобрить</a>' ?></p>
                <? } ?>
                <p></p>
            </div>
        </div>

    <? }
    echo LinkPager::widget([
        'pagination' => $pager,
        'options' => [
            'class' => 'pagination pagination-lg'
        ]
    ]);
    ?>
</div>