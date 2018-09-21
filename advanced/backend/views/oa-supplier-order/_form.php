<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="oa-form">
    <div class="ibox-content form-horizontal">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'update-form',
                'method' => 'post',
                'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-6">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'inputOptions' => ['class' => 'form-control']
                ]
            ]
        );
        ?>
        <div class="form-group">
            <h4 class="col-sm-offset-2"><?php echo '订单总金额：'.$totalAmt ?></h4>
        </div>
        <div class="form-group">
            <?php if($model->img){
                echo Html::img($model->img, ['alt' => '缩略图', 'width' => '200px','class' => 'image-view col-sm-offset-2']);
            }else{
                echo Html::img(Url::to("@web/img/noImg.jpg"), ['alt' => '缩略图', 'width' => '200px','class' => 'image-view col-sm-offset-2']);
            } ?>
        </div>

        <?php echo $form->field($model, 'img', ['template' => "<font color='red'>{label}</font><div class=\"col-sm-8\">{input}{error}</div>",])->fileInput(['placeholder' => '--必填--']) ?>
        <?php echo $form->field($model, 'paymentAmt', ['template' => "<font color='red'>{label}</font><div class=\"col-sm-8\">{input}{error}</div>",])->textInput(['placeholder' => '--必填--']) ?>
        <?php echo $form->field($model, 'comment', ['template' => '{label}<div class="col-sm-8">{input}{error}</div>'])->textInput() ?>
        <?php echo $form->field($model, 'unpaidAmt', ['template' => '{label}<div class="col-sm-8">{input}{error}</div>'])->textInput(['readonly' => 'true', 'placeholder' => '--自动计算--']) ?>

        <div class="form-group">
            <?= Html::submitButton('提交', ['id' => 'create-btn', 'class' => 'btn btn-primary col-sm-offset-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$createUrl = Url::toRoute(['oa-goods/forward-create', 'type' => 'create',]);
$createCheckUrl = Url::toRoute(['oa-goods/forward-create', 'type' => 'check',]);
$js = <<< JS
//监听备货按钮事件


JS;

$this->registerJs($js);

?>
