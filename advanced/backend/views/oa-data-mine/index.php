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

    <div class="col-lg-4">
        <div class="input-group">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">商品编号</button>
      </span>
            <input id='pro-id' name="proId" type="text" class="form-control" placeholder="1504779018437136151-176(多个用逗号隔开)">
            <input name="platform" type="text" value="joom" hidden="hidden">
        </div><!-- /input-group -->
    </div><!-- /.col-lg-6 -->
    <div class="col-lg-4">
        <button type="submit" class="btn btn-success">开始采集</button>
        <button type="button" class="btn export-lots-btn btn-danger">批量导出Joom-csv</button>
        <button type="button" class="btn complete-lots-btn btn-warning">批量标记完善</button>
    </div><!-- /.col-lg-6 -->
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
                ['class' => 'yii\grid\ActionColumn'],
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
//                            'value' => Yii::$app->request->get('OaDataMineSearch')['createTime'],
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
                'goodsCode'
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

JS;
$this->registerJs($js);
?>