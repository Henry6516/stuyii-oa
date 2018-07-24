<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
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
class OaSupplierGoods extends ActiveRecord
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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdTime', 'updatedTime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updatedTime'],
                ],
                 'value' => new Expression('getDate()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
//            'id' => 'ID',
            'supplier' => '供应商名称',
            'purchaser' => '线下采购员',
            'goodsCode' => '商品编码',
            'goodsName' => '商品名称',
            'supplierGoodsCode' => '供应商商品名称',
            'createdTime' => '创建时间',
            'updatedTime' => '更新时间',
        ];
    }
}
