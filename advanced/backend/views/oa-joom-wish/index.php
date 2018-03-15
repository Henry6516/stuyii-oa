<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OaJoomWishSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Joom对比Wish价格';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-wish-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建条目', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
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
