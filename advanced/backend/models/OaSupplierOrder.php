<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierOrder".
 *
 * @property int $id
 * @property string $supplierName
 * @property string $goodsCode
 * @property string $billNumber
 * @property string $billStatus
 * @property string $purchaser
 * @property string $syncTime
 * @property int $totalNumber
 * @property string $amt
 * @property string $expressNumber
 * @property string $paymentStatus
 * @property string $orderTime
 * @property string $createdTime
 * @property string $updatedTime
 */
class OaSupplierOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierOrder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplierName', 'goodsCode', 'billNumber', 'billStatus', 'purchaser', 'expressNumber', 'paymentStatus'], 'string'],
            [['syncTime', 'orderTime', 'createdTime', 'updatedTime'], 'safe'],
            [['totalNumber'], 'integer'],
            [['amt'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplierName' => '供应商名称',
            'goodsCode' => '商品编码',
            'billNumber' => '采购单号',
            'billStatus' => '采购状态',
            'purchaser' => '线下采购',
            'syncTime' => '同步时间',
            'totalNumber' => '采购总数',
            'amt' => '采购金额',
            'expressNumber' => '快递单号',
            'paymentStatus' => '支付状态',
            'orderTime' => '下单时间',
            'createdTime' => '创建时间',
            'updatedTime' => '更新时间',
        ];
    }
}
