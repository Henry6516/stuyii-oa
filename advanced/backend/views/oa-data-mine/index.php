<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use \kartik\form\ActiveForm;
use yii\widgets\Pjax;
use \yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OaDataMineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据采集';
$this->params['breadcrumbs'][] = $this->title;
$createJobUrl = URl::toRoute('create-job')
?>
<link rel="stylesheet" href="../css/bootstrap-select.min.css">
<div class="oa-data-mine-index">

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Joom',
                'url' => Url::to(['index']),
                'headerOptions' => ["id" => 'tab1'],
                'options' => ['id' => 'all-task'],
                'active' => true,
            ],
            [
                'label' => 'Wish',
                'url' => Url::to(['unfinished']),
                'headerOptions' => ["id" => 'tab2'],
                'options' => ['id' => 'unfinished-task'],
            ],
            [
                'label' => 'Aliexpress',
                'url' => Url::to(['finished']),
                'headerOptions' => ["id" => 'tab3'],
                'options' => ['id' => 'finished-task'],
            ],

        ],
    ]); ?>
    <div class="row" style="margin: 1%">
        <?php $form = ActiveForm::begin([
            'action' => $createJobUrl,
            'method' => 'post',
            'id' => 'create-job',
            'enableAjaxValidation' => true,
            'options' => ['data-pjax' => true ],
        ]); ?>

    <div class="col-lg-3">
        <div class="input-group">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">商品编号</button>
      </span>
            <input id='pro-id' name="proId" type="text" class="form-control" placeholder="15047790-18437136151(多个用逗号隔开)">
            <input name="platform" type="text" value="joom" hidden="hidden">
        </div><!-- /input-group -->
    </div><!-- /.col-lg-6 -->
    <div class="col-lg-3">
        <button type="submit" class="btn btn-success">开始采集</button>
        <button type="button" class="btn export-lots-btn btn-danger">批量导出Joom-csv</button>
        <button type="button" class="btn complete-lots-btn btn-warning">批量标记完善</button>

    </div><!-- /.col-lg-6 -->
    <div class="col-lg-6">
        <div class="col-sm-4">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="op-btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">=<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a class="operation" href="javascript:void(0);"><span style="font-size: 17px">=</span></a></li>
                        <li><a class="operation" href="javascript:void(0);"><span style="font-size: 17px">+</span></a></li>
                        <li><a class="operation" href="javascript:void(0);"><span style="font-size: 17px">-</span></a></li>
                        <li><a class="operation" href="javascript:void(0);"<span style="font-size: 17px">*</span></a></li>
                        <li><a class="operation" href="javascript:void(0);"<span style="font-size: 17px">/</span></a></li>
                    </ul>
                </div><!-- /btn-group -->
                <div class="input-group" >
                    <input type="text" class="price-replace form-control"   placeholder="--设置价格--">
                    <span class="input-group-btn">
                                <button id="price-set" class="btn btn-default" type="button">确定</button>
                            </span>
                </div>
            </div>
        </div>
        <div class=" cat col-sm-2">
            <select class="selectpicker"  data-width="100%" >
                <?php
                echo '<option value="">--主类目--</option>';
                foreach ($cat as $name ){
                    echo "<option value='{$name}'>{$name}</option>";
                }
                ?>
            </select>

        </div>
        <div class=" sub-cat col-sm-2">
            <select class="selectpicker"  data-width="100%">
                <option value="">--子类目--</option>
            </select>

        </div>

        <div class="col-sm-3">
            <button class="set-cat btn" type="button">确定类目</button>

        </div>

    </div>


        <?php ActiveForm::end(); ?>
</div><!-- /.row -->


    <?php Pjax::begin(['id' => 'job-table']) ?>
    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' =>'mine-table',
            'pjax' => 'true',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                ['class' => 'yii\grid\CheckboxColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {send} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open" title="View Details"></span>', $url, ['data-pjax' => 0, 'target' => "_blank"]);
                        },
                        'update' => function ($url, $model) {

                            return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>',$url, ['data-pjax' => 0, 'target' => "_blank"]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash" title= "Delete"></span>', $url, ['data-pjax' => 0,]);
                        },
                        'send' => function ($url, $model) {
                            return Html::a('<span class=" send glyphicon glyphicon-share-alt" title= "Send"></span>', $url, []);
                        },
                    ],
                ],
                [   'attribute' => 'varMainImage',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:80px; white-space: normal;'],
                    'value' => function($model,$key)
                    {
                        $image = $model->oa_data_mine_detail?$model->oa_data_mine_detail->MainImage:'';
                        $anchor = 'https://joom.com/en/products/'.$model->proId ;
                        return "<div align='center'><a target='_blank' href='{$anchor}'> <img  src='{$image}' width='60' height='60'></a></div>";
                    },
                    'label' => '图片',
                ],
                'proId',
                'platForm',
                'progress',
                'creator',
                ['attribute' => 'createTime',
                    'format' => 'raw',
                    'value' => function($model) {
                        return substr((string)$model->createTime,0,10);

                    },
                    'width' => '200px',
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => [
                        'pluginOptions' => [
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
                        ],
//                        'presetDropdown' => true,
                        'pluginEvents' => [
                            'apply.daterangepicker' => 'function(ev, picker) {
                             var val = picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format);
                             $(picker.element[0]).val(val);
                             $(picker.element[0]).trigger("change");
                             }',
                            'cancel.daterangepicker' => 'function(ev,picker){
                             $(picker.element[0]).val("");
                             $(picker.element[0]).trigger("change");
                                }'
                        ],
                        'model' =>$searchModel,
                        'attribute' => 'createTime',

                    ],

                ],
                ['attribute' => 'updateTime',
                    'format' => 'raw',
                    'value' => function($model) {
                        return substr((string)$model->updateTime,0,10);

                    },
                    'width' => '200px',
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => [
                        'pluginOptions' => [
//                            'value' => Yii::$app->request->get('OaDataMineSearch')['updateTime'],
                            'convertFormat' => true,
//                            'defaultPresetValueOptions' => ['style'=>'display:none'],
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
                        ],
                        'pluginEvents' => [
                            'apply.daterangepicker' => 'function(ev, picker) {
                             var val = picker.startDate.format(picker.locale.format) + picker.locale.separator + picker.endDate.format(picker.locale.format);
                             $(picker.element[0]).val(val);
                             $(picker.element[0]).trigger("change");
                             }',
                            'cancel.daterangepicker' => 'function(ev,picker){
                             $(picker.element[0]).val("");
                             $(picker.element[0]).trigger("change");
                                }'
                        ],
                        'model' =>$searchModel,
                        'attribute' => 'createTime',

                    ],

                ],
                ['attribute' => 'detailStatus',
                    'format' => 'raw',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ['未完善' =>'未完善', '已完善' => '已完善'],
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '-请选择-'],

                ],
                ['attribute' => 'devStatus',
                    'format' => 'raw',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ['未开发' =>'未开发','开发中' => '开发中', '已开发' => '已开发'],
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '-请选择-'],

                ],
                ['attribute' => 'cat',
                    'format' => 'raw',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => array_combine($cat, $cat),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '-请选择-'],

                ],
                ['attribute' => 'subCat',
                    'format' => 'raw',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => array_combine($subCat, $subCat),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '-请选择-'],

                ],
                'goodsCode',
                'pyGoodsCode'
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
        }
        catch (\Exception $why){

        }?>
    <?php Pjax::end() ?>
</div>

<?php
$exportLotsUrl = Url::toRoute(['export-lots']);
$completeLotsUrl = Url::toRoute(['complete-lots']);
$subCatUrl = Url::toRoute(['sub-cat']);
$setPriceUrl = Url::toRoute(['set-price']);
$setCatUrl = Url::toRoute(['set-cat']);
$sendUrl = Url::toRoute(['send']);

$js = <<< JS

/*
create job
 */
$('form#create-job').on('beforeSubmit', function() {
    
    if($('#pro-id').val()===''){
        alert('商品编号不可为空！');
        return false
    }
    var this_form = $(this);
    $.ajax({
    url:this_form.attr('action'),
    dataType: 'json',
    data:this_form.serialize(),
    type:'POST',
    success:function(res) {
        var msg = res['msg']; 
        alert(msg);
        $.pjax.reload({container:"#job-table",timeout: 5000});
    }
    });
}).on('submit',function(e) {
    e.preventDefault();
});

/*
export lots
 */
$('.export-lots-btn').on('click', function() {
    var lots_mid = $('#mine-table').yiiGridView("getSelectedRows");
    window.location = '$exportLotsUrl' + '?lots_mid='+ lots_mid;
    return false;
})

/*
complete lots
 */
$('.complete-lots-btn').on('click', function() {
    var lots_mid = $('#mine-table').yiiGridView('getSelectedRows');
    $.ajax({
        url: '$completeLotsUrl',
        type: 'post',
        data:({'lots_mid': lots_mid}),
        success:(function(ret) {
            alert(ret);
            $.pjax.reload({container:"#job-table"});
        })
    });
})

/*
cat selection
 */
$('.cat select').on('change',function() {
    var cat = $('.cat option:selected').val();
    $('.sub-cat option').not(':first').remove();
    $.get("$subCatUrl",{cat:cat},function(cats) {
        var select = $('.sub-cat select');
        $.each(JSON.parse(cats), function(index,value) {
            var html = '<option value="'+ value +'">'+ value +'</option>';
            select.append(html);
        })
        select.selectpicker('refresh');
    });
})


/*
set price
 */

//operator
$('.operation').on('click',function() {
    var op = $(this).text()
    var button = $('.op-btn');
    button.html(button.html().replace(button.html()[0],op));
})

$('#price-set').on('click',function() {
    var price_replace = $('.price-replace').val()?parseFloat($('.price-replace').val()):0;
    var op = $('.op-btn').html()[0]
    var lots_mid = $('#mine-table').yiiGridView("getSelectedRows");
    if(lots_mid.length === 0) {
        alert('请选中产品！');
        return false;   
    }
    $.ajax({
        url: '{$setPriceUrl}',
        type: 'post',
        data:({'op':op,'lots_mid':lots_mid,'price_replace':price_replace}),
        success:(function(ret) {
            alert(ret);
        })
    });
    
    
    
})

/*
set cat
 */
$('.set-cat').on('click',function() {
    var cat = $('.cat select option:selected').val();
    var sub_cat = $('.sub-cat select option:selected').val();
    if(cat.length === 0 ||sub_cat.length === 0){
        alert('请选择类目！');
        return false;
    }
    var lots_mid = $('#mine-table').yiiGridView("getSelectedRows");
    if(lots_mid.length === 0) {
        alert('请选中产品！');
        return false;   
    }
    $.ajax({
        url:'{$setCatUrl}',
        type:'post',
        data:({'cat':cat,'sub_cat':sub_cat,'lots_mid':lots_mid}),
        success:(function(ret) {
            alert(ret);
        })
    })
    
})



// send it to developer

$('.send').on('click', function() {
    var mid = $(this).closest('tr').attr('data-key');
    console.log(mid);
    $.ajax({
        url:'{$sendUrl}',
        type: 'post',
        data:({mid:mid}),
        success:(function(ret) {
            alert(ret);
        })
        
    });
  
})
JS;

$this->registerJs($js);
?>


