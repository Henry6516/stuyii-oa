<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */

$this->title = '创建规则';
$this->params['breadcrumbs'][] = ['label' => 'Joom对比Wish价格', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-wish-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
