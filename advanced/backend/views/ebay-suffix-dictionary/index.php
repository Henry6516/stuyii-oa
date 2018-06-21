<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WishSuffixDictionarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'eBay账号字典';
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'index-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal" >关闭</a>',
    'size' => "modal-lg",
    'options' => [
        'data-backdrop' => 'static',//点击空白处不关闭弹窗
        'data-keyboard' => false,
    ],
]);
//echo
Modal::end();

$js = <<<JS
        $('.index-create').on('click', function () {
            $('.modal-body').children('div').remove();
            var url = $(this).data('href');
            $.get(url, function (msg) {
                 $('.modal-body').html(msg);
            })
        });
        $('.index-update').on('click', function () {
            $('.modal-body').children('div').remove();
            var url = $(this).data('href');
            $.get(url, function (msg) {
                 $('.modal-body').html(msg);
            })
        });
        $('.index-view').on('click', function () {
            $('.modal-body').children('div').remove();
            var url = $(this).data('href');
            $.get(url, function (msg) {
                 $('.modal-body').html(msg);
            })
        });    
JS;

$this->registerJs($js);

?>
<div class="wish-suffix-dictionary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?php //echo Html::a('添加ebay账号', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('添加eBay账号', "#", ['title' => 'create', 'data-toggle' => 'modal', 'data-target' => '#index-modal',
            'data-href' => Url::to(['create']), 'class' => 'index-create btn btn-primary']) ?>
    </p>
    <?php //Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => false,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width' => '200'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'javascript:void(0);',
                            [
                                'data-href' => Url::to(['view', 'id' => $model['nid']]),
                                'title' => '查看',
                                'aria-label' => '查看',
                                'data-toggle' => 'modal',
                                'data-target' => '#index-modal',
                                'class' => 'index-view'
                            ]
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javascript:void(0);',
                            [
                                'data-href' => Url::to(['update', 'id' => $model['nid']]),
                                'title' => '更新',
                                'aria-label' => '更新',
                                'data-toggle' => 'modal',
                                'data-target' => '#index-modal',
                                'class' => 'index-update'
                            ]
                        );
                    },
                ]
            ],
            //'nid',
            'ebayName',
            'ebaySuffix',
            'storeCountry',
            'nameCode',
            'mainImg',
            'ibayTemplate',
            [
                'attribute' => 'highEbayPaypal',
                'value' => function ($model) {
                    $id = $model->nid;
                    $arr = \backend\models\OaEbayPaypal::find()->joinWith('payPal')->andWhere(['ebayId' => $id, 'maptype' => 'high'])->asArray()->one();
                    //var_dump($arr);exit;
                    if ($arr) {
                        return $arr['payPal']['paypalName'];
                    }
                },
                //'payPal.paypalName',
            ],
            [
                'attribute' => 'lowEbayPaypal',
                'value' => function ($model) {
                    $id = $model->nid;
                    $arr = \backend\models\OaEbayPaypal::find()->joinWith('payPal')->andWhere(['ebayId' => $id, 'maptype' => 'low'])->asArray()->one();
                    //var_dump($arr);exit;
                    if ($arr) {
                        return $arr['payPal']['paypalName'];
                    }
                },
            ],
        ],
    ]); ?>
    <?php //Pjax::end(); ?>
</div>


