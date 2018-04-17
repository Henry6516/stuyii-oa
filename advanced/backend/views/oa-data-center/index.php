<?php
use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Wish待刊登');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-index">
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
        'striped'=>true,
        'responsive'=>true,
        'hover'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\CheckboxColumn'],
            //['class' => 'yii\grid\ActionColumn'],

            [
                'attribute' => 'mainImage',
                'value' =>function($model,$key, $index, $widget) {
                    try{
                        $image = $model->oa_templates->mainPage;
                    }
                    catch (Exception $e){
                        $image = $model->picUrl;
                    }

                    return "<img src='{$image}' width='100' height='100'/>";
                },
                'label' => '主图',
                'format' => 'raw',
            ],
            [
                'attribute' => 'GoodsCode',
                'format' => 'raw',
                'value' => function ($model) {
                    if($model->stockUp) {
                        return '<strong style="color:red">'. $model->GoodsCode.'</strong>';
                    }
                    return $model->GoodsCode;
                }
            ],
            [
                'attribute' => 'wishpublish',
                //'value'=>'oa_goods.cate',
                'width' => '150px',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['Y' => 'Y', 'N' => 'N'],
                //'filter'=>ArrayHelper::map(\backend\models\OaGoodsinfo::find()->orderBy('pid')->asArray()->all(), 'pid', 'IsLiquid'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '-请选择-'],
                //'group'=>true,  // enable grouping
            ],
             'GoodsName',
             'developer',
             'Purchaser',
             'possessMan1',
            [
                'attribute' => 'devDatetime',
                'value' => function ($model) {
                    return substr(strval($model->devDatetime),0,20);
                },
            ],
            'DictionaryName',
            'isVar',
            'number',
            [
                'attribute' => 'goodsstatus',
                //'value'=>'oa_goods.cate',
                'width' => '150px',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $goodsStatusList,
                //'filter'=>ArrayHelper::map(\backend\models\OaGoodsinfo::find()->orderBy('pid')->asArray()->all(), 'pid', 'IsLiquid'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '-请选择-'],
                //'group'=>true,  // enable grouping
            ],
            'stockdays'
        ],
        'pager' =>[
            'class' => \common\widgets\MLinkPager::className(),
            'firstPageLabel' => '首页',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '尾页',
            'goPageLabel' => true,
            'goPageSizeArr' => ['10' => 10,'20' => 20,'50' => 50,'100' => 100,'500' => 500,'1000' => 1000],
            'totalPageLable' => '共x页',
            'goButtonLable' => '确定',
            'maxButtonCount' => 10
        ],
    ]); ?>
</div>
