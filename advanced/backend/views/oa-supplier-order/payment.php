<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '付款明细';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="oa-supplier-order-index" style="margin-top: 1%;">

    <?php Pjax::begin(['id' => 'order-table']) ?>
    <?php
    Modal::begin([
        'id' => 'payment-modal',
        'header' => '<h4 class="modal-title">付款</h4>',
        'footer' => '<a href="#" class="mod-act btn btn-primary" data-dismiss="modal">关闭</a>',
    ]);
    Modal::end();
    ?>
    <div class="form-group">
        <h3 class="col-sm-offset-3"><?php echo '订单总金额：'.$totalAmt ?></h3>
    </div>
    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'id' => 'supplier-order-view',
            'pjax' => 'true',
            'options' => ['data-pjax' => 'order-table'],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{payment} ',
                    'buttons' => [
                        'payment' => function ($url, $model, $key)use($isShowPayButton) {
                            return $isShowPayButton ? Html::a('<span class="btn btn-primary">付款</span></a>',
                                '#',
                                ['class' => 'payment-btn', 'type' => 'button', 'data-status' => $model->paymentStatus,
                                    'title' => '付款', 'aria-label' => '付款','data-id'=>$model->id,
                                    'data-toggle' => 'modal','data-target' => '#payment-modal']) : '';
                        },
                    ],
                ],
                [
                    'attribute' => 'img',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->img && file_exists($model->img)?
                            Html::a(Html::img($model->img, ['alt' => '缩略图', 'height' => 50]), $model->img, ['target' => '_blank', 'class' => 'image-view'])
                             : Html::a(Html::img(Url::to("@web/img/noImg.jpg"), ['alt' => '缩略图', 'width' => 50]), '#', ['class' => 'image-view']);
                    }
                ],
                [
                    'attribute' => 'billNumber',
                    'pageSummary' => 'pageSummary',
                ],
                [
                    'attribute' => 'requestTime',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'requestAmt',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'paymentStatus',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'comment',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'paymentTime',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'paymentAmt',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'unpaidAmt',
                    'pageSummary' => true,
                    'value' => function($unpaidAmt)use ($unpaidAmt){
                        return $unpaidAmt;
                    }
                ],
            ],
        ]);
    } catch (Exception  $why) {
        throw new \Exception($why);
    }
    ?>
    <?php Pjax::end() ?>
    <?php
    $viewUrl = Url::toRoute(['save-payment']);
    $js = <<<JS
    //显示修改输入框
    $('.payment-btn').on('click',function() {
        var status = $(this).data('status');
        if(status === '已支付'){
            alert('该申请已经付款，请勿重复操作！');
            return false;
        }
        $("#payment-modal").modal('hide');
        $('.modal-body').children('div').remove();
        $.get('{$viewUrl}',  { id: $(this).data('id') },
            function (data) {
                $("#payment-modal").modal('show'); 
                $('.modal-body').html(data);
            }
        );
    });

JS;

    $this->registerJs($js);
    ?>

</div>

<style>
    .kv-panel-after {
        float: right;
    }

    .image-view img {
        /*width: 250px;*/
        transition: .1s transform;
        transform: translateZ(0);
    }

    .image-view a:hover {
        width: 250px;
        overflow: visible;
    }

    .image-view img:hover {
        position: absolute;
        z-index: 1000;
        transform: scale(4, 4);
        transition: .3s transform;
    }
</style>




