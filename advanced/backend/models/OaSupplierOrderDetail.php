<?php

namespace backend\models;

use Yii;
/**
 * This is the model class for table "oa_supplierOrderDetail".
 *
 * @property int $id
 * @property int $orderId
 * @property string $sku
 * @property string $image
 * @property string $supplierGoodsSku
 * @property string $goodsName
 * @property string $property1
 * @property string $property2
 * @property string $property3
 * @property int $purchaseNumber
 * @property int $goodsCode
 * @property string $purchasePrice
 * @property int $deliveryNumber
 */
class OaSupplierOrderDetail extends \yii\db\ActiveRecord
{
    public $billNumber;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierOrderDetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'purchaseNumber', 'deliveryAmt'], 'integer'],
            [['sku', 'goodsCode', 'image', 'supplierGoodsSku', 'goodsName', 'property1', 'property2', 'property3',
                'deliveryNumber', 'deliveryStatus', 'paymentStatus'], 'string'],
            [['purchasePrice'], 'number'],
            [['deliveryTime','billNumber'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => '订单主表ID',
            'sku' => 'SKU',
            'goodsCode' => '商品编码',
            'image' => '图片',
            'supplierGoodsSku' => '供应商SKU',
            'goodsName' => '商品名称',
            'property1' => '款式1',
            'property2' => '款式2',
            'property3' => '款式3',
            'purchaseNumber' => '采购数量',
            'purchasePrice' => '采购价格',
            'deliveryAmt' => '发货数量',
            'deliveryNumber' => '物流单号',
            'deliveryStatus' => '发货状态',
            'paymentStatus' => '发货状态',
            'deliveryTime' => '发货时间',
            'billNumber' => '采购单号',
        ];
    }

    /**
     * @brief join with oa_supplierOrder
     */
    public function getOa_SupplierOrder() {
        return $this->hasOne(OaSupplierOrder::className(),['id'=>'orderId']);
    }
}
