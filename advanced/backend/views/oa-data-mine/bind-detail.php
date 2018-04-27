<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-04-02 17:12
 */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id'=>'detail',
    'method'=>'post',])
?>

<?php

try{
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'detail-table',
        'form'=>$form,
        'attributes'=>[

            'childId'=>['label'=>'唯一编码', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'childId'],
                'columnOptions'=>['width'=>'170px']
            ],
            'color'=>['label'=>'颜色', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'color'],
            ],
            'proSize'=>['label'=>'尺码/型号', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'proSize'],
            ],
            'pySku'=>['label'=>'普源SKU', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'pySku'],
            ],
            'varMainImage'=>['label'=>'图片', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'varMainImage'],
            ],
            'image' =>[
                'label' =>'图片',
                'type' => TabularForm::INPUT_RAW,
                'options' => ['class' => 'image'],
                'value' => function($model){
                    return '<img width="50" height="50" src="'.$model->varMainImage.'">';
                }
            ]


        ],

        // configure other gridview settings
        'gridSettings'=>[
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'footer'=>false,
            ]
        ]

    ]);
    ActiveForm::end();

}

catch (\Exception $why) {

}


?>

<?php

$js = <<< JS

JS;
$this->registerJs($js);
?>

<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 70%;
            /*max-width:1200px;*/
        }
    }

    .panel-primary > .panel-heading {
        color: black;
        background-color: whitesmoke;
        border-color: transparent;
    }
    .panel-primary {
        border-color: transparent;
    }

    .panel-footer {
        padding: 10px 15px;
        background-color: white;
        border-top: 1px solid #ddd;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }

   
</style>
