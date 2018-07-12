<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '平台信息');
$this->params['breadcrumbs'][] = $this->title;
\backend\assets\AppAsset::addJs($this,'plugins/bootstrap-select/bootstrap-select.min.js');
\backend\assets\AppAsset::addCss($this,'plugins/bootstrap-select/bootstrap-select.min.css');
?>
<!--<link rel="stylesheet" href="../css/bootstrap-select.min.css">-->

<div class="channel-index" style="">
    <div class="row">
        <div class="col-sm-2">
         <select class="selectpicker joom-chosen" data-actions-box="true"  title="--请选择账号--">
            <?php
            foreach ($joomAccount as $account) {
                echo '<option class="ebay-select" value="' . $account . '">' . $account . '</option>';
            }
            ?>
            </select>
        </div>
        <div class="col-sm-1">
            <?= Html::button(Yii::t('app', '导出Joom模板'),
                ['class' => 'export-joom btn']) ?>
        </div>
        <div class="col-sm-1">
        <?= Html::button(Yii::t('app', '标记Wish已完善'),
            ['class' => 'wish-sign-lots btn btn-info', 'data-href' => Url::toRoute(['wish-sign-lots'])]) ?>
        </div>
        <div class="col-sm-1">
        <?= Html::button(Yii::t('app', '标记eBay已完善'),
            ['class' => 'ebay-sign-lots btn btn-primary', 'data-href' => Url::toRoute(['ebay-sign-lots'])]) ?>
        </div>
        <div class="col-sm-1">

        <?= Html::button(Yii::t('app', '标记Joom已完善'),
            ['class' => 'joom-sign-lots btn btn-success', 'data-href' => Url::toRoute(['joom-sign-lots'])]) ?>
        </div>

        <div class="col-sm-1">
        <?= Html::button(Yii::t('app', '标记全部已完善'),
            ['class' => 'all-sign-lots btn btn-warning', 'data-href' => Url::toRoute(['all-sign-lots'])]) ?>
        </div>

    </div>
</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'chanel-table',
//        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
        'striped'=>true,
        //'responsive'=>true,
        'hover'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\ActionColumn'],

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
                    $value = $data->stockUp?'是':'否';
                    return "<span class='cell'>" . $value . "</span>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => [0 =>'否', 1 => '是'],
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
                    'data' => ['未设置' => '未设置', 'eBay已完善' => 'eBay已完善', 'Wish已完善' => 'Wish已完善','Joom已完善' => 'Joom已完善',
                        'Wish已完善|eBay已完善' => 'Wish已完善|eBay已完善',
                        'Wish已完善|Joom已完善' => 'Wish已完善|Joom已完善',
                        'Joom已完善|eBay已完善' => 'Joom已完善|eBay已完善',
                        'Wish已完善|eBay已完善|Joom已完善' => 'Wish已完善|eBay已完善|Joom已完善'
                    ],
                    "options" => [
                            'id' => 'complete-status',
                            'multiple'=>"multiple",
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
                'value'=>'oa_goods.cate',
                'width' => '150px',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\GoodsCats::findAll(['CategoryParentID' => 0]),'CategoryName', 'CategoryName'),
                //'filter'=>ArrayHelper::map(\backend\models\OaGoodsinfo::find()->orderBy('pid')->asArray()->all(), 'pid', 'IsLiquid'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '-请选择-'],
                //'group'=>true,  // enable grouping
            ],
            [
                'attribute'=> 'subCate',
                'value'=>'oa_goods.subCate'
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
                'attribute'=> 'introducer',
                'value'=>'oa_goods.introducer'
            ],
             'developer',
             'Purchaser',
             'possessMan1',
            [
                'attribute' => 'mid',
                'width' => '150px',
                'format' => 'raw',
                'value' => function ($data) {
                    $value = $data->mid?'是':'否';
                    return "<span class='cell'>" . $value . "</span>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ['N' =>'否', 'Y' => '是'],
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
                        'locale'=>[
                            'format' => 'YYYY-MM-DD',
                            'separator'=>'/',
                            'applyLabel' => '确定',
                            'cancelLabel' => '取消',
                            'daysOfWeek'=>false,
                        ],
                        'opens'=>'left',
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
                        'locale'=>[
                            'format' => 'YYYY-MM-DD',
                            'separator'=>'/',
                            'applyLabel' => '确定',
                            'cancelLabel' => '取消',
                            'daysOfWeek'=>false,
                        ],
                        'opens'=>'left',
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
    ]);

    ?>


<?php
$joomUrl = Url::toRoute(['export-lots-joom']);

$js = <<< JS

//默认选中项
// var data = 'eBay已完善,Wish已完善,Joom已完善';
var data = '{$selectedStatus}';
var valArr = data.split(",");
var i = 0, size = valArr.length;
for (i; i < size; i++) {
    $('#channelsearch-completestatus').multiselect('select', valArr[i]);
}



/*
批量导出joom模板
 */

//joom模板

$('.joom-btn').on('click', function() {
    alert('确定导出Joom模板?');
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    var flag = 'first';
    window.location.href = '{$joomUrl}' + '?pids=' + pids + '&flag=' + flag;
})


//joom2模板
$('.joom-sec-btn').on('click', function() {
    alert('确定导出Joom模板?');
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    var flag = 'second';
    window.location.href = '{$joomUrl}' + '?pids=' + pids + '&joom=' + joom;
})

//选择账号后导出Joom模板
$('.export-joom').on('click',function() {
    var joom = $('.joom-chosen select option:selected').val();
    if( joom===null ||joom===undefined ||joom==='') {
        alert("请选择Joom账号！");
        return false;
    }
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if( pids===null ||pids===undefined ||pids.length===0) {
        alert("请选中产品！");
        return false;
    }
    window.location.href = '{$joomUrl}' + '?pids=' + pids + '&suffix=' + joom;
})
//批量标记Wish已完善
$('.wish-sign-lots').on('click', function() {
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if(pids.length == 0){
        krajeeDialog.alert("请选择要标记的选项！")
        return false;
    }
    krajeeDialog.confirm('确定批量标记Wish已完善?',function(res) {
        if(res){
            $.ajax({
                type:"POST",
                url:$('.wish-sign-lots').data('href'),
                data:{id:pids},
                success:function(data) {
                    alert(data);
                    location.reload()
                }
            });
        }
    })
})
//批量标记eBay已完善
$('.ebay-sign-lots').on('click', function() {
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if(pids.length == 0){
        krajeeDialog.alert("请选择要标记的选项！")
        return false;
    }
    krajeeDialog.confirm('确定批量标记eBay已完善?',function(res) {
        if(res){
            $.ajax({
                type:"POST",
                url:$('.ebay-sign-lots').data('href'),
                data:{id:pids},
                success:function(data) {
                    alert(data);
                    location.reload()
                }
            });
        }
    })
})
//批量标记Joom已完善
$('.joom-sign-lots').on('click', function() {
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if(pids.length == 0){
        krajeeDialog.alert("请选择要标记的选项！")
        return false;
    }
    krajeeDialog.confirm('确定批量标记Joom已完善?',function(res) {
        if(res){
            $.ajax({
                type:"POST",
                url:$('.joom-sign-lots').data('href'),
                data:{id:pids},
                success:function(data) {
                    alert(data);
                    location.reload()
                }
            });
        }
    })
})

//批量标记Joom已完善
$('.all-sign-lots').on('click', function() {
    var pids = $('#chanel-table').yiiGridView("getSelectedRows");
    if(pids.length == 0){
        krajeeDialog.alert("请选择要标记的选项！")
        return false;
    }
    krajeeDialog.confirm('确定批量标记全部已完善?',function(res) {
        if(res){
            $.ajax({
                type:"POST",
                url:$('.all-sign-lots').data('href'),
                data:{id:pids},
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



