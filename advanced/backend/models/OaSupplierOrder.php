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
 * @property string $expressStatus
 * @property string $deliveryStatus
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
            [['supplierName', 'goodsCode', 'billNumber', 'billStatus','deliveryStatus','expressStatus', 'purchaser', 'expressNumber', 'paymentStatus'], 'string'],
            [['syncTime', 'orderTime', 'createdTime', 'updatedTime'], 'safe'],
            [['totalNumber'], 'integer'],
            [['amt'], 'number'],
            [['billNumber'], 'required'],
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
            'billStatus' => '采购单状态',
            'purchaser' => '线下采购',
            'syncTime' => '同步时间',
            'totalNumber' => '总数量',
            'amt' => '总金额',
            'deliveryStatus' => '发货状态',
            'expressNumber' => '物流单号',
            'paymentStatus' => '支付状态',
            'expressStatus' => '物流状态',
            'orderTime' => '下单时间',
            'createdTime' => '创建时间',
            'updatedTime' => '更新时间',
        ];
    }
}
