<?php

use yii\helpers\Html;
use yii\grid\GridView;
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
            <input id='pro-id' name="proId" type="text" class="form-control" placeholder="1504779018437136151-176-1-26193-2505906393">
            <input name="platform" type="text" value="joom" hidden="hidden">
        </div><!-- /input-group -->
    </div><!-- /.col-lg-6 -->
    <div class="col-lg-4">
        <button type="submit" class="btn btn-success">开始采集</button>
    </div><!-- /.col-lg-6 -->
        <?php ActiveForm::end(); ?>
</div><!-- /.row -->


    <?php Pjax::begin(['id' => 'job-table']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
            'createTime',
            'updateTime',
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>

<?php
$js = <<< JS

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
      $.pjax.reload({container:"#job-table",timeout: false});
    }
    });
}).on('submit',function(e) {
    e.preventDefault();
});


JS;
$this->registerJs($js);
?>