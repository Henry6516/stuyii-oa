<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */

$this->title = '更新规则';
$this->params['breadcrumbs'][] = ['label' => 'Joom对比Wish价格', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nid, 'url' => ['view', 'id' => $model->nid]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="oa-joom-wish-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
