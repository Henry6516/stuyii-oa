<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-06-13 14:33
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    $this->render('_form', [
        'model' => $model,
        'plats' => $plats,
    ])
    ?>
</div>
