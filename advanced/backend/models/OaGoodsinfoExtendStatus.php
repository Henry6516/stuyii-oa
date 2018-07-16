<?php

namespace backend\models;

use Yii;

/**
 * @property integer $id
 */
class OaGoodsinfoExtendStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'oa_goodsinfo_extend_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goodsinfo_id'], 'integer'],
            [['status','saler', 'createtime'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goodsinfo_id' => Yii::t('app', '商品ID'),
            'status' => Yii::t('app', '推广状态'),
            'saler' => Yii::t('app', '销售员'),
            'createtime' => Yii::t('app', '推广时间')
        ];
    }


}



