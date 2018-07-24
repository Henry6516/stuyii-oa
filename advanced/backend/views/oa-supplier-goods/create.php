<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoods */

$this->title = 'Create Oa Supplier Goods';
$this->params['breadcrumbs'][] = ['label' => 'Oa Supplier Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-goods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
