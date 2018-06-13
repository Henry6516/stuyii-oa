<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-06-13 14:18
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $url = Url::toRoute(['update','role' => $model->name]);
                        return Html::a('<span  class="glyphicon glyphicon-pencil"></span>',$url , []);
                    },
                ]
            ],
        ]
    ]);
    ?>
</div>
