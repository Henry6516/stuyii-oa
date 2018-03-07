<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_task_attribute_log".
 *
 * @property int $id 自增
 * @property int $pid 商品ID,对应oa_goodsinfo表pid
 * @property string $GoodsCode 修改后的商品编码
 * @property string $description 修改后的描述
 * @property string $createtime 修改时间
 */
class OaTaskAttributeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_task_attribute_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid'], 'integer'],
            [['oldGoodsCode', 'GoodsCode', 'oldDescription', 'description'], 'string'],
            [['createtime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'GoodsCode' => 'Goods Code',
            'description' => 'Description',
            'createtime' => 'Createtime',
        ];
    }
}
