<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "oa_supplier".
 *
 * @property int $id
 * @property string $supplierName
 * @property string $contactPerson1
 * @property string $phone1
 * @property string $contactPerson2
 * @property string $phone2
 * @property string $address
 * @property string $link1
 * @property string $link2
 * @property string $link3
 * @property string $link4
 * @property string $link5
 * @property string $link6
 * @property string $paymentDays
 * @property string $payChannel
 * @property string $purchase
 * @property string $createtime
 * @property string $updatetime
 */
class OaSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplierName', 'purchase'], 'required'],
            [['supplierId'], 'integer'],
            [['supplierName', 'contactPerson1', 'phone1', 'contactPerson2', 'phone2', 'address',
                'link1', 'link2', 'link3', 'link4', 'link5', 'link6', 'paymentDays', 'payChannel', 'purchase'], 'string'],
            [['createtime', 'updatetime'], 'safe'],
        ];
    }

    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createtime',// 自己根据数据库字段修改
                'updatedAtAttribute' => 'updatetime', // 自己根据数据库字段修改
                'value' => date('Y-m-d H:i:s',time()), // 自己根据数据库字段修改
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'supplierName' => '供应商名称',
            'contactPerson1' => '联系人1',
            'phone1' => '电话1',
            'contactPerson2' => '联系人2',
            'phone2' => '电话2',
            'address' => '地址',
            'link1' => '店铺链接1',
            'link2' => '店铺链接2',
            'link3' => '店铺链接3',
            'link4' => '店铺链接4',
            'link5' => '店铺链接5',
            'link6' => '店铺链接6',
            'paymentDays' => '账期',
            'payChannel' => '付款渠道',
            'purchase' => '线下采购',
            'createtime' => '添加时间',
            'updatetime' => '更新时间',
        ];
    }
}
