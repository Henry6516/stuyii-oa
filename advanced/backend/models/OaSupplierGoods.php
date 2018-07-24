<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierGoods".
 *
 * @property int $id
 * @property string $supplier
 * @property string $purchaser
 * @property string $goodsCode
 * @property string $goodsName
 * @property string $supplierGoodsCode
 * @property string $createdTime
 * @property string $updatedTime
 */
class OaSupplierGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierGoods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier', 'purchaser', 'goodsCode', 'goodsName', 'supplierGoodsCode'], 'string'],
            [['createdTime', 'updatedTime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier' => 'Supplier',
            'purchaser' => 'Purchaser',
            'goodsCode' => 'Goods Code',
            'goodsName' => 'Goods Name',
            'supplierGoodsCode' => 'Supplier Goods Code',
            'createdTime' => 'Created Time',
            'updatedTime' => 'Updated Time',
        ];
    }
}
