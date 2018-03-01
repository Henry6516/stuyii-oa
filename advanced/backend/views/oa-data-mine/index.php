<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OaDataMineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oa Data Mines';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-data-mine-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oa Data Mine', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'proId',
            'platForm',
            'progress',
            'creator',
            //'createTime',
            //'updateTime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
