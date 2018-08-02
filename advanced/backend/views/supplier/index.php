<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商管理';
$this->params['breadcrumbs'][] = $this->title;

if (!isset($status)) $status = '';

?>
<div class="oa-supplier-index">

    <div class="col-md-12" style="margin-top: 1%">
        <div class="box box-info">
            <div class="box-header with-border">
                <p><?= Html::a('添加新供应商', ['create'], ['class' => 'create btn btn-success',]) ?></p>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'yii\grid\ActionColumn'],
                    'purchase',
                    'supplierName',
                    'contactPerson1',
                    'phone1',
                    'contactPerson2',
                    'phone2',
                    'paymentDays',
                    'payChannel',
                    'address',
                    'link1',
                    'link2',
                    //'link3',
                    //'link4',
                    //'link5',
                    //'link6',
                    //'createtime',
                    //'updatetime',
                ],
            ]); ?>

        </div>
    </div>
</div>
