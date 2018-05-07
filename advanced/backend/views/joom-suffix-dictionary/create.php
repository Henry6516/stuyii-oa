<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaJoomSuffix */

//$this->title = '添加Joom账号';
//$this->params['breadcrumbs'][] = ['label' => 'Oa Joom Suffixes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-suffix-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
