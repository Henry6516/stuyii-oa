<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OaJoomWishSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oa Joom Wishes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-wish-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oa Joom Wish', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nid',
            'greater_equal',
            'less',
            'added_price',
            'createDate',
            'updateDate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
