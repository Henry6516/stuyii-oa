<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */

$this->title = '创建产品';
$this->params['breadcrumbs'][] = ['label' => '产品推荐', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-goods-create">


    <?= $this->render('_formForwardCreate', [
        'model' => $model,
        'canStock' => $canStock,
        'canCreate' => $canCreate,
    ]) ?>

</div>
