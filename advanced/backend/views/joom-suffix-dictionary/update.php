<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaJoomSuffix */

//$this->title = '编辑Joom账号';
//$this->params['breadcrumbs'][] = ['label' => 'Joom账号字典', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->nid, 'url' => ['view', 'id' => $model->nid]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oa-joom-suffix-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
