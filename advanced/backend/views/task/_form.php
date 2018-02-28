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
AppAsset::addCss($this, [
        'plugins/zTree/css/tree.css',
        'plugins/zTree/css/zTreeStyle/zTreeStyle.css']
);
AppAsset::addJs($this, [
        'plugins/zTree/js/jquery.ztree.core.js',
        'plugins/zTree/js/jquery.ztree.excheck.js']
);
$user = isset($user) ? $user : '';
$js = <<<JS
    //内容
    if ($('#container').val() != undefined) {
        var ue = UE.getEditor('container',{
            initialFrameHeight: 400
        });
    }
    //设置执行人初始值(修改任务)
    if('{$user}'.length > 0){
        var arr = JSON.parse('{$user}');
        $(".search__li").before(createTab(arr));
    }
    
    
    
    //初始化选择树插件
    var setting = {
			check: {
				enable: true,
				nocheckInherit: true,
				chkDisabledInherit: true
			},
			view: {
				showIcon: false
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				beforeClick: beforeClick,
				onCheck: onCheck
			}
		};

		var zNodes = {$userList};
		
		function beforeClick(treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.checkNode(treeNode, !treeNode.checked, null, true);
            return false;
        }
       
        
        function onCheck(e, treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                nodes = zTree.getCheckedNodes(true),
                v = "",
                id = [];
            for (var i=0, l=nodes.length; i<l; i++) {
                if(!parseInt(nodes[i].id)) {
                    continue;
                }else {
                    id.push(nodes[i].id);
                    v += '<li class="point-remove selection__choice" title="' + nodes[i].name + 
                    '" data-id="' + nodes[i].id + '"><span class="selection__choice__remove">x</span>'+ nodes[i].name + '</li>'
                }
            }
            $(".point-remove").remove();
            $(".search__li").before(v);
            $("#oatask-sendee").val(id);
        }
    
        //单个删除已选
		$('.selection__choice__remove').on('click',function() {
		    var id = $(this).parent().data('id');
		    //$(this).parent().remove();//删除li标签
		   
		    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getCheckedNodes();
            $.each(nodes,function(i,item) {
                if(item.id == id){
                    treeObj.checkNode(nodes[i], false, true, true);
                }
            });
		})
		
		//删除全部已选
		$('.selection__clear').on('click',function() {
		    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getCheckedNodes();
            $.each(nodes,function(i,item) {
                if(nodes.length > 0){
                    treeObj.checkNode(nodes[i], false, true, true);
                }
            });
		})
		
		$(document).ready(function(){
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    });
JS;
$this->registerJs($js);
?>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
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

        <div class="form-group">
            <label class="control-label col-md-1"><span style="color: red"> 执行人： </span></label>
            <div class="col-md-6">
                <div class="left">
                    <div id="citySel" class="userSelected" readonly="true" onclick="showMenu();">
                        <ul class="selection__rendered">
                            <span class="selection__clear">x</span>
                            <li class="search__li" style="float: left">
                                <input type="text" class="search__field">
                            </li>
                        </ul>
                    </div>
<!--                    <input id="userSel" name="OaTask[sendee]" type="hidden">-->
                </div>
                <div id="menuContent" class="menuContent" style="display:none; position: absolute;z-index:1000">
                    <ul id="treeDemo" class="ztree"></ul>
                </div>
            </div>
        </div>

        <?= $form->field($model, 'sendee', ['template' => '{label}<div class="col-sm-6 col-sm-offset-1">{input}{error}</div>',])
            ->hiddenInput()->label(false) ?>

        <div class="form-group">
            <label class="control-label col-md-1">内容:</label>
            <div class="col-md-6">
                <script id="container" name="OaTask[description]"
                        type="text/plain"><?php echo $model->description; ?></script>
            </div>
        </div>


        <div class="form-group" style="margin-left: 8%">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<script>


    function showMenu() {
        /*var cityObj = $("#citySel");
        var cityOffset = $("#citySel").offset();
        $("#menuContent").css({
            left: cityOffset.left + "px",
            top: cityOffset.top + cityObj.outerHeight() + "px"
        });*/
        $("#menuContent").slideDown("fast");
        $("body").bind("mousedown", onBodyDown);
    }

    function hideMenu() {
        $("#menuContent").fadeOut("fast");
        $("body").unbind("mousedown", onBodyDown);
    }

    function onBodyDown(event) {
        if (!(event.target.id == "citySel" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length > 0)) {
            hideMenu();
        }
    }
    function createTab(arr) {
        var str = '';
        $.each(arr,function(i,item) {
            str += '<li class="point-remove selection__choice" title="' + item + '" data-id="' + i
                + '"><span class="selection__choice__remove">x</span>'+ item + '</li>'
        });
        return str;
    }
</script>




