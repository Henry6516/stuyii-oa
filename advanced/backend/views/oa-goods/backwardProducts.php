<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\dialog\Dialog;
use \backend\models\GoodsCats;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaGoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '逆向产品';
$this->params['breadcrumbs'][] = $this->title;
//创建模态框

use yii\bootstrap\Modal;
Modal::begin([
    'id' => 'backward-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-lg",
    'options'=>[
        'data-backdrop'=>'static',//点击空白处不关闭弹窗
        'data-keyboard'=>false,
    ],
]);
//echo
Modal::end();

//模态框的方式查看和更改数据
$viewUrl = Url::toRoute('forward-view');
$updateUrl = Url::toRoute('backward-update');
$createUrl = Url::toRoute('backward-create');
$approve = Url::toRoute('approve');
$approveLots = Url::toRoute('approve-lots');
$deleteUrl = Url::toRoute('delete');
$deleteLots = Url::toRoute('delete-lots');
$js = <<<JS
$('.glyphicon-eye-open').addClass('icon-cell');
$('.wrapper').addClass('body-color');
// 查看框
$('.backward-view').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$viewUrl}',  { id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });
    
//删除按钮
$('.backward-delete').on('click',  function () {
     self = this;
     krajeeDialog.confirm("确定删除此条记录?", function (result) {
        if (result) {
            id = $(self).closest('tr').data('key');
            $.post('{$deleteUrl}',{id:id},function(res) {
                alert(res);
                window.location.reload();
            });
            }
            });
        });
//更新框
$('.backward-update').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$updateUrl}',  {id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });

//创建框
$('.backward-create').on('click',  function () {
        $('.modal-body').children('div').remove();
        $.get('{$createUrl}',
            function (data) {
                $('.modal-body').html(data);
            }
        );
    }); 

//提交审核
$('.approve').on('click',function(){
     var id = $(this).closest('tr').data('key');
        krajeeDialog.confirm("确定提交审核?", function (result) {
        if (result) {
           $.get("{$approve}", {'id':id,'type':'backward-products'},
               function(msg){
                  alter(msg); 
               }               
           );
            
        } else {
            alert('Oops!放弃提交');
        }
    });
});

//批量提交审批
$('.approve-lots').on('click',function() {
    var ids = $("#oa-check").yiiGridView("getSelectedRows");    
    if(ids.length == 0) return false;
     $.ajax({
           url:"{$approveLots}",
           type:"post",
           data:{'id':ids,'type':'backward-products'},
           dataType:"json",
           success:function(res){               
                console.log("oh yeah lots passed!");
           }
        });
    });

//批量删除
   $('.delete-lots').on('click',function() {
     var ids = $("#oa-check").yiiGridView("getSelectedRows");
      if(ids.length == 0) return false;
      $.ajax({
          url:"{$deleteLots}",
          type:"post",
          data:{'id':ids},
          dataType:"json",
          success:function(result) {
            alert(result);
            window.location.reload();
          }
      });
   });
   
JS;
$this->registerJs($js);
//单元格居中类
class CenterFormatter {
    public function __construct($name) {
        $this->name = $name;
    }
    public  function format() {
        // 超链接显示为超链接
        if ($this->name === 'origin'||$this->name === 'origin1'||$this->name === 'origin1'
            ||$this->name === 'origin2'||$this->name === 'origin3'||$this->name === 'vendor1'||$this->name === 'vendor2'
            ||$this->name === 'vendor3') {
            return  [
                'attribute' => $this->name,
                'value' => function($data) {
                    if(!empty($data[$this->name]))
                    {
                        try {
                            $hostName = parse_url($data[$this->name])['host'];
                        }
                        catch (Exception $e){
                            $hostName = "www.unknown.com";
                        }
                        return "<a class='cell' href='{$data[$this->name]}' target='_blank'>{$hostName}</a>";
                    }
                    else
                    {
                        return '';
                    }

                },
                'format' => 'raw',

            ];
            // 图片显示为图片
        }
        if ($this->name === 'img') {
            return [
                'attribute' => 'img',
                'value' => function($data) {
                    return "<img src='".$data[$this->name]."' width='100' height='100'>";
                },
                'format' => 'raw',

            ];
        }
        if (strpos(strtolower($this->name), 'date') || strpos(strtolower($this->name), 'time')) {
            return [
                'attribute' => $this->name,
                'value' => function($data) {
                    return "<span class='cell'>".substr($data[$this->name],0,10)."</span>";

                },
                'format' => 'raw',

            ];

        }
        return  [
            'attribute' => $this->name,
            'value' => function($data) {
                return "<span class='cell'>".$data[$this->name]."</span>";
                    return $data['cate'];
            },
            'format' => 'raw',


        ];
    }
};
//封装到格式化函数中
function centerFormat($name) {
    return (new CenterFormatter($name))->format();
};
?>
<style>
    .cell {
        Word-break: break-all;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        width: 100px;
        height: 100px;
        /*border:1px solid #666;*/
    }

    .icon-cell {
        Word-break: break-all;
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        width: 10px;
        height: 100px;
    }
    .body-color {
        background-color: whitesmoke;
    }
</style>
<div class="oa-goods-index">
   <!-- 页面标题-->
    <p>
        <?= Html::a('新增产品',"javascript:void(0);",  ['title'=>'create','data-toggle' => 'modal','data-target' => '#backward-modal','class' => 'backward-create btn btn-primary']) ?>
        <?= Html::a('批量导入', "javascript:void(0);", ['title' => 'upload', 'class' => 'upload btn btn-info']) ?>
        <?= Html::a('批量删除',"javascript:void(0);",  ['title'=>'deleteLots','class' => 'delete-lots btn btn-danger']) ?>
        <?= Html::a('下载模板', ['template'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('批量审批',"javascript:void(0);",  ['title'=>'approveLots','class' => 'approve-lots btn btn-warning']) ?>
        <input type="file" id="import" name="import" style="display: none" >
    </p>
    <?= GridView::widget([
        'bootstrap' => true,
        'responsive'=>true,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'oa-check',
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
        ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            ['class' => 'kartik\grid\SerialColumn'],

            [ 'class' => 'kartik\grid\ActionColumn',
                'template' =>'{view} {update} {delete} {approve}',
                'buttons' => [

                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => '删除',
                            'aria-label' => '删除',
                            'data-id' => $key,
                            'class' => 'backward-delete',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-trash"></span>', 'javascript:void(0)', $options);
                    },
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => '查看',
                            'aria-label' => '查看',
                            'data-toggle' => 'modal',
                            'data-target' => '#backward-modal',
                            'data-id' => $key,
                            'class' => 'backward-view',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '#', $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => '更新',
                            'aria-label' => '更新',
                            'data-toggle' => 'modal',
                            'data-target' => '#backward-modal',
                            'data-id' => $key,
                            'class' => 'backward-update',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-pencil"></span>', '#', $options);
                    },
                    'approve' => function ($url, $model, $key) {
                        $options = [
                            'title' => '提交审核',
                            'aria-label' => '提交审核',
                            'data-id' => $key,
                            'class' => 'approve',
                        ];
                        return Html::a('<span  class="glyphicon  glyphicon-check"></span>', '#', $options);
                    },
                ],
            ],
            centerFormat('img'),
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
            //centerFormat('cate'),
            [
                'attribute' => 'cate',
                'width' => '150px',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<span class='cell'>" . $data->cate . "</span>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(GoodsCats::findAll(['CategoryParentID' => 0]),'CategoryName', 'CategoryName'),
                //'filter'=>ArrayHelper::map(\backend\models\OaGoodsinfo::find()->orderBy('pid')->asArray()->all(), 'pid', 'IsLiquid'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '-请选择-'],
                //'group'=>true,  // enable grouping
            ],
            centerFormat('subCate'),
            centerFormat('vendor1'),
            centerFormat('origin1'),
            centerFormat('devNum'),
            centerFormat('developer'),
            centerFormat('introducer'),
            centerFormat('introReason'),
            //centerFormat('checkStatus'),
            [
                'attribute' => 'checkStatus',
                'width' => '100px',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class='cell'>" . strval($model->checkStatus) . "</span>";
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['已认领' => '已认领','待审批' => '待审批', '待提交' => '待提交', '已审批' => '已审批', '未通过' => '未通过'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions' => ['placeholder' => '产品状态'],
            ],
            centerFormat('approvalNote'),
            //centerFormat('createDate'),
            //centerFormat('updateDate'),
            [
                'attribute' => 'createDate',
                'format' => 'raw',
                //'format' => ['date', "php:Y-m-d"],
                'value' => function ($model) {
                    return "<span class='cell'>" . substr(strval($model->createDate), 0, 10) . "</span>";
                },
                'width' => '200px',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'value' => Yii::$app->request->get('OaGoodsSearch')['createDate'],
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
                'attribute' => 'updateDate',
                'label' => '更新时间',
                'format' => "raw",
                'value' => function ($model) {
                    return "<span class='cell'>" . substr(strval($model->updateDate), 0, 10) . "</span>";
                },
                'width' => '200px',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'value' => Yii::$app->request->get('OaGoodsSearch')['updateDate'],
                        'convertFormat' => true,
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
            centerFormat('salePrice'),
            centerFormat('hopeWeight'),
            centerFormat('hopeRate'),
            centerFormat('hopeSale'),
            centerFormat('hopeMonthProfit'),
        ],
    ]); ?>
</div>

