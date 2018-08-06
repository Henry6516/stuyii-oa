<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        认领到
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><?= Html::a('正向认领',Url::toRoute(['forward','id' => $model->nid])) ?></li>
        <li><?= Html::a('逆向认领',Url::toRoute(['backward','id' => $model->nid])) ?></li>
    </ul>
</div>


