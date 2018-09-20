<?php

namespace backend\models;

use console\models\Send;
use Yii;

/**
 * This is the model class for table "oa_supplierOrderPaymentDetail".
 *
 * @property int $id
 * @property string $billNumber 订单编号
 * @property string $requestTime 请求时间
 * @property string $requestAmt 请求金额
 * @property string $paymentStatus 付款状态 已付款 未付款
 * @property string $paymentTime 付款时间
 * @property string $paymentAmt 付款金额
 * @property string $img 付款凭证（截图）
 * @property string $comment 备注
 * @property string $unpaidAmt 未付金额
 */
class OaSupplierOrderPaymentDetail extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_supplierOrderPaymentDetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['billNumber'], 'required'],
            [['billNumber', 'paymentStatus', 'img', 'comment'], 'string'],
            [['requestTime', 'paymentTime'], 'safe'],
            [['requestAmt', 'paymentAmt', 'unpaidAmt'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'billNumber' => '订单编号',
            'requestTime' => '请求付款时间',
            'requestAmt' => '请求付款金额',
            'paymentStatus' => '付款状态',
            'paymentTime' => '付款时间',
            'paymentAmt' => '付款金额',
            'img' => '凭证',
            'comment' => '备注',
            'unpaidAmt' => '未付金额',
        ];
    }

    /**
     * 关联供应商订单
     * @return \yii\db\ActiveQuery
     */
    public function getOa_SupplierOrder() {
        return $this->hasOne(OaSupplierOrder::className(),['billNumber'=>'billNumber']);
    }


    /**
     * 发送邮件
     * @param $id
     */
    public function send($id){
        $user = User::findOne(['username' => '汪薇']);
        $title = '供应商订单付款';
        $content = '<div>'.
            $user['username'].'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有新的商品订单需要付款,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-check/to-check">http://58.246.226.254:8010/oa-supplier-order/payment?id=' .
            $id . '</a></p></div>';
        Send::sendEmail($user['username'],$user['email'],$title,$content);
    }

}
