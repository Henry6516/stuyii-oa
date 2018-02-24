<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \backend\assets\AppAsset;
use \kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\OaTask */
/* @var $form yii\widgets\ActiveForm */
//加载 富文本框js
AppAsset::addEdit($this);

$js = <<<JS
    //内容
    if ($('#container').val() != undefined) {
        var ue = UE.getEditor('container',{
            initialFrameHeight: 400
        });
    }
JS;
$this->registerJs($js);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<div class="oa-task-form form-horizontal">
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'id' => 'task-form',
            'options' => ['class' => 'form-horizontal'],
            'method' => 'post',
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-6">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-1 control-label'],
                'inputOptions' => ['class' => 'form-control']
            ]
        ]); ?>


        <?= $form->field($model, 'title')->textInput()->label('<span style="color: red"> 标题： </span>') ?>

        <?= $form->field($model, 'sendee')->widget(Select2::classname(), [
            'data' => $userList,//ArrayHelper::map($categoryParent, 'id', 'name'),
            'language' => 'zh',
            'options' => ['multiple' => true, 'placeholder' => '选择执行人'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('<span style="color: red"> 执行人： </span>') ?>

        <!--<div class="form-group">
            <label class="control-label col-md-1">执行人:</label>
            <div class="col-sm-4">
                <select class="col-sm-4 selectpicker user-chosen-up" multiple data-actions-box="true" title="--所有人--" name="">
                    <option class="user-select" value="">请选择</option>
                    <?php /*foreach ($sendee = [1,2,3,4,5,6] as $k => $value) { */?>
                        <option class="user-select" value="'<?php /*echo $value */?>'"><?php /*echo $value */?></option>;
                    <?php /*} */?>
                </select>
            </div>
        </div>-->

        <div class="form-group">
            <label class="control-label col-md-1">内容:</label>
            <div class="col-md-6">
                <script id="container" name="OaTask[description]" type="text/plain"><?php echo $model ->description;?></script>
            </div>
        </div>


        <div class="form-group" style="margin-left: 8%">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>





