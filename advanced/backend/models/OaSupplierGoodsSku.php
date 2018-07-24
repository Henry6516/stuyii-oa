<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierGoodsSku".
 *
 * @property int $id
 * @property int $supplierGoodsId
 * @property string $sku
 * @property string $property1
 * @property string $property2
 * @property string $property3
 * @property string $costPrice
 * @property string $purchasPrice
 * @property string $weight
 * @property string $image
 * @property string $lowestPrice
 * @property int $purchasNumber
 * @property string $supplierGoodsSku
 */
class OaSupplierGoodsSku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierGoodsSku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplierGoodsId', 'purchasNumber'], 'integer'],
            [['sku', 'property1', 'property2', 'property3', 'image', 'supplierGoodsSku'], 'string'],
            [['costPrice', 'purchasPrice', 'weight', 'lowestPrice'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplierGoodsId' => 'Supplier Goods ID',
            'sku' => 'Sku',
            'property1' => '款式1',
            'property2' => '款式2',
            'property3' => '款式3',
            'costPrice' => '成本价',
            'purchasPrice' => '采购价',
            'weight' => '重量',
            'image' => '图片',
            'lowestPrice' => '近三个月最低采购价',
            'purchasNumber' => '最低价采购数量',
            'supplierGoodsSku' => '供应商产品SKU',
        ];
    }
}
