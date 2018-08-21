<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '销售产品列表');
$this->params['breadcrumbs'][] = $this->title;
\backend\assets\AppAsset::addJs($this, 'plugins/bootstrap-select/bootstrap-select.min.js');
\backend\assets\AppAsset::addCss($this, 'plugins/bootstrap-select/bootstrap-select.min.css');

?>
<!--<link rel="stylesheet" href="../css/bootstrap-select.min.css">-->

<div class="channel-index" style="">
    <div class="row">
        <div class="col-sm-1">
            <?= Html::button(Yii::t('app', '批量标记推广完成'),
                ['class' => 'extend-lots btn btn-info', 'data-href' => Url::toRoute(['extend-lots'])]) ?>
        </div>

    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'chanel-table',
//        'pjax'=>true,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ],
    'striped' => true,
    //'responsive'=>true,
    'hover' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {extend}',
            'buttons' => [
                'extend' => function ($url, $model, $key) {
                    $options = [
                        'title' => '标记推广完成',
                        'aria-label' => '标记推广',
                        'data-id' => $key,
                        'class' => 'index-extend',
                        'data-href' => Url::toRoute(['/channel/extend']),
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-check"></span>', '#', $options);
                },
            ],
        ],

        [
            'attribute' => 'mainImage',
            'value' => function ($model, $key, $index, $widget) {
                try {
                    $image = $model->oa_templates->mainPage;
                } catch (Exception $e) {
                    $image = $model->picUrl;
                }

                return "<img src='{$image}' width='100' height='100'/>";
            },
            'label' => '主图',
            'format' => 'raw',
        ],
        [
            'attribute' => 'extendStatus',
            'label' => '推广状态',
            'format' => 'raw',
            'value' => function ($model) use ($role, $user) {
                /*if(strpos($role, '开发') !== false){
                   return $model->extendStatus ? $model->extendStatus : '未推广';
                }else*/ if(strpos($model->mapPersons, $user) !== false) {
                    $res = yii::$app->db->createCommand("SELECT status FROM [oa_goodsinfo_extend_status] 
                    WHERE  goodsinfo_id=" . $model->pid . " and saler='{$user}'")->queryOne();
                    //var_dump($res);exit;
                    return $res && $res['status'] ? $res['status'] : '未推广';
                } else {
                    return Html::a($model->extendStatus ? $model->extendStatus : '未推广',
                        'javascript:void(0);',
                        [
                            'title' => '查看详情', 'data-toggle' => 'modal',
                            'data-target' => '#index-modal',
                            'data-url' => Url::toRoute(['extend-view']),
                            'class' => 'admin-extend-status'
                        ]);
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['已推广' => '已推广', '未推广' => '未推广'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '-请选择-'],
        ],
        [
            'attribute' => 'GoodsCode',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->stockUp) {
                    return '<strong style="color:red">' . $model->GoodsCode . '</strong>';
                }
                return $model->GoodsCode;
            }
        ],
        'mapPersons',
        [
            'attribute' => 'StoreName',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $stores,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '-请选择-'],
        ],
        [
            'attribute' => 'stockUp',
            'width' => '150px',
            'format' => 'raw',
            'value' => function ($data) {
                $value = $data->stockUp ? '是' : '否';
                return "<span class='cell'>" . $value . "</span>";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [0 => '否', 1 => '是'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '-请选择-'],
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
        [
            'attribute' => 'completeStatus',
            'width' => '500px',
            'filterType' => '\dosamigos\multiselect\MultiSelect',
            'filterWidgetOptions' => [
                'data' => ['未设置' => '未设置', 'eBay已完善' => 'eBay已完善', 'Wish已完善' => 'Wish已完善', 'Joom已完善' => 'Joom已完善',
                    'Wish已完善|eBay已完善' => 'Wish已完善|eBay已完善',
                    'Wish已完善|Joom已完善' => 'Wish已完善|Joom已完善',
                    'Joom已完善|eBay已完善' => 'Joom已完善|eBay已完善',
                    'Wish已完善|eBay已完善|Joom已完善' => 'Wish已完善|eBay已完善|Joom已完善'
                ],
                "options" => [
                    'id' => 'complete-status',
                    'multiple' => "multiple",
                ],
                "clientOptions" =>
                    [
                        'numberDisplayed' => 1,
                        'nonSelectedText' => '--请选择--',

                    ],
            ],
        ],
        'GoodsName',
        [
            'attribute' => 'cate',
            'value' => 'oa_goods.cate',
            'width' => '150px',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\GoodsCats::findAll(['CategoryParentID' => 0]), 'CategoryName', 'CategoryName'),
            //'filter'=>ArrayHelper::map(\backend\models\OaGoodsinfo::find()->orderBy('pid')->asArray()->all(), 'pid', 'IsLiquid'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '-请选择-'],
            //'group'=>true,  // enable grouping
        ],
        [
            'attribute' => 'subCate',
            'value' => 'oa_goods.subCate'
        ],
        [
            'attribute' => 'SupplierName',
            'width' => '100px',
            'format' => 'raw',
            'headerOptions' => ['width' => '100px'],
            'value' => function ($model) {
                return mb_substr(strval($model->SupplierName), 0, 10) . "<br>" . mb_substr(strval($model->SupplierName), 10, 30);
            },
        ],
        [
            'attribute' => 'introducer',
            'value' => 'oa_goods.introducer'
        ],
        'developer',
        'Purchaser',
        'possessMan1',
        [
            'attribute' => 'mid',
            'width' => '150px',
            'format' => 'raw',
            'value' => function ($data) {
                $value = $data->mid ? '是' : '否';
                return "<span class='cell'>" . $value . "</span>";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ['N' => '否', 'Y' => '是'],
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '-请选择-'],
        ],
        'DictionaryName',
        [
            'attribute' => 'devDatetime',
            'format' => 'raw',
            //'format' => ['date', "php:Y-m-d"],
            'value' => function ($model) {
                return "<span class='cell'>" . substr(strval($model->devDatetime), 0, 10) . "</span>";
            },
            'width' => '200px',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'value' => Yii::$app->request->get('ChannelSearch')['devDatetime'],
                    'convertFormat' => true,
                    'useWithAddon' => true,
                    'format' => 'php:Y-m-d',
                    'todayHighlight' => true,
                    'locale' => [
                        'format' => 'YYYY-MM-DD',
                        'separator' => '/',
                        'applyLabel' => '确定',
                        'cancelLabel' => '取消',
                        'daysOfWeek' => false,
                    ],
                    'opens' => 'left',
                    //起止时间的最大间隔
                    /*'dateLimit' =>[
                        'days' => 300
                    ]*/
                ]
            ]
        ],
        [
            'attribute' => 'updateTime',
            'format' => 'raw',
            //'format' => ['date', "php:Y-m-d"],
            'value' => function ($model) {
                return "<span class='cell'>" . substr(strval($model->updateTime), 0, 10) . "</span>";
            },
            'width' => '200px',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'value' => Yii::$app->request->get('ChannelSearch')['updateTime'],
                    'convertFormat' => true,
                    'useWithAddon' => true,
                    'format' => 'php:Y-m-d',
                    'todayHighlight' => true,
                    'locale' => [
                        'format' => 'YYYY-MM-DD',
                        'separator' => '/',
                        'applyLabel' => '确定',
                        'cancelLabel' => '取消',
                        'daysOfWeek' => false,
                    ],
                    'opens' => 'left',
                    //起止时间的最大间隔
                    /*'dateLimit' =>[
                        'days' => 300
                    ]*/
                ]
            ]
        ],
        'isVar',
        //'goodsstatus',
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
    'pager' => [
        'class' => \common\widgets\MLinkPager::className(),
        'firstPageLabel' => '首页',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '尾页',
        'goPageLabel' => true,
        'goPageSizeArr' => ['10' => 10, '20' => 20, '50' => 50, '100' => 100, '500' => 500, '1000' => 1000],
        'totalPageLable' => '共x页',
        'goButtonLable' => '确定',
        'maxButtonCount' => 10
    ],
]);

?>
<?php
//创建模态框
use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'index-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-lg",
    'options' => [
        'data-backdrop' => 'static',//点击空白处不关闭弹窗
        'data-keyboard' => false,
    ],
]);
Modal::end();


$js = <<< JS

//默认选中项
// var data = 'eBay已完善,Wish已完善,Joom已完善';
var data = '{$selectedStatus}';
var valArr = data.split(",");
var i = 0, size = valArr.length;
for (i; i < size; i++) {
    $('#channelsearch-completestatus').multiselect('select', valArr[i]);
}
//查看推广详情
$('.admin-extend-status').on('click', function() {
    $('.modal-body').children('div').remove();
    $.get($('.admin-extend-status').data('url'),{ id: $(this).closest('tr').data('key') },
        function (data) {
            $('.modal-body').html(data);
        }
    );
})


//批量标记推广完成
$('.extend-lots').on('click', function() {
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if(pids.length == 0){
        krajeeDialog.alert("请选择要标记的选项！")
        return false;
    }
    krajeeDialog.confirm('确定批量标记推广完成?',function(res) {
        if(res){
            $.ajax({
                type:"POST",
                url:$('.extend-lots').data('href'),
                data:{id:pids},
                success:function(data) {
                    alert(data);
                    location.reload()
                }
            });
        }
    })
})


//单个标记推广完成
$('.index-extend').on('click', function() {
    id = $(this).closest('tr').data('key');
    console.log($('.index-extend').data('href'));
    krajeeDialog.confirm('确定标记推广完成?',function(res) {
        if(res){
            $.ajax({
                type:"get",
                url:$('.index-extend').data('href'),
                data:{id:id},
                success:function(data) {
                    alert(data);
                    location.reload()
                }
            });
        }
    })
})

JS;

$this->registerJs($js);

?>



