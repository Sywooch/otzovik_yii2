<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать отзыв', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(['timeout' => 2000]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'Salon',
                'value' => 'salon.title',
            ],
            'author',
            'text:ntext',
            'rate',
            // 'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{delete}{approve}',
                'buttons' => [
                    'view',
                    'update',
                    'delete',
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Одобрить'),
                            'aria-label' => Yii::t('yii', 'approve'),
                        ];
                        $url = \yii\helpers\Url::toRoute([
                            'comments/approve',
                            'id' => $model->id,
                            'approved' => $model->status == 1 ? '0' : '1',
                        ]);

                        return Html::a($model->status == 1 ? '<span class="glyphicon glyphicon-remove"></span>' : '<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    }
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
