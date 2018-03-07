<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%oa_goodssku}}".
 *
 * @property integer $sid
 * @property integer $pid
 * @property string $sku
 * @property string $property1
 * @property string $property2
 * @property string $property3
 * @property string $CostPrice
 * @property double $Weight
 * @property string $RetailPrice
 * @property string $memo1
 * @property string $memo2
 * @property string $memo3
 * @property string $memo4
 */
class Goodssku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oa_goodssku}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['pid'], 'required'],
            [['pid'], 'integer'],
            [['sku', 'property1', 'property2', 'property3', 'memo1', 'memo2', 'memo3', 'memo4', 'CostPrice', 'Weight', 'RetailPrice', 'linkurl'], 'string'],
            [['linkurl', 'stockNum'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => 'skuID',
            'pid' => '产品id',
            'sku' => 'Sku',
            'property1' => '颜色',
            'property2' => '大小',
            'property3' => '款式3',
            'CostPrice' => '成本价',
            'Weight' => '重量',
            'RetailPrice' => '零售价',
            'memo1' => '备注1',
            'memo2' => '备注2',
            'memo3' => '备注3',
            'memo4' => '备注4',
            'stockNum' => '备货数量',
        ];
    }

    /**
     * 获取任务接收人
     * @inheritdoc
     */
    public static function getTaskSendee($id, $unit)
    {
        $wishSendee = $ebaySendee = [];
        $model = OaGoodsinfo::findOne(['pid' => $id]);
        if (stripos($model['completeStatus'], "Wish已完善") !== false) {
            if($unit == '图片地址修改'){
                $wishSql = "SELECT user_id FROM auth_assignment a ".
                    " LEFT JOIN [user] u ON a.user_id=u.id WHERE ISNULL(u.username,'')<>'' AND ".
                    " item_name IN ('产品开发','产品开发2','Wish销售','SMT销售')";
            }else{
                $wishSql = "SELECT user_id FROM auth_assignment a ".
                    " LEFT JOIN [user] u ON a.user_id=u.id WHERE ISNULL(u.username,'')<>'' AND ".
                    " item_name IN ('Wish销售','SMT销售')";
            }
            $wishSendee = Yii::$app->db->createCommand($wishSql)->queryAll();
        }
        if (stripos($model['completeStatus'], "eBay已完善") !== false) {
            $wishSql = "SELECT user_id FROM auth_assignment a ".
                " LEFT JOIN [user] u ON a.user_id=u.id WHERE ISNULL(u.username,'')<>'' AND item_name = 'eBay销售'";
            $ebaySendee = Yii::$app->db->createCommand($wishSql)->queryAll();
        }
        $sendee = array_merge($wishSendee, $ebaySendee);
        return $sendee;
    }

    /**
     * 获取商品片吗或描述修改日志
     * @inheritdoc
     */
    public static function getGoodsAttrLog($id)
    {
        $list = OaTaskAttributeLog::find()->where(['pid' => $id])->orderBy('createtime ASC')->asArray()->all();
        $times = count($list);//修改记录数
        $str = '';
        if($times > 1){
            $first = $list[0];
            $last = end($list);
            //print_r($first);
            //print_r($last);exit;
            if($first['oldGoodsCode'] != $last['GoodsCode']){
                $str .= '<tr><td>修改商品编码</td><td>原商品编码:'. $first['oldGoodsCode'] .'</td><td>修改后的商品编码:'. $last['GoodsCode'] .'</td></tr>';
            }
            if($first['oldDescription'] != $last['description']){
                $str .= '<tr><td>修改商品描述</td><td>原商品描述:'. $first['oldDescription'] .'</td><td>修改后的商品描述:'. $last['description'] .'</td></tr>';
            }
        }elseif($times == 1){
            if($list[0]['oldGoodsCode'] != $list[0]['GoodsCode']){
                $str .= '<tr><td>修改商品编码</td><td>原商品编码:'. $list[0]['oldGoodsCode'] .'</td><td>修改后的商品编码:'. $list[0]['GoodsCode'] .'</td></tr>';
            }
            if($list[0]['oldDescription'] != $list[0]['description']){
                $str .= '<tr><td></td>修改商品描述<td>原商品描述:'. $list[0]['oldDescription'] .'</td><td>修改后的商品描述:'. $list[0]['description'] .'</td></tr>';
            }
        }
        return $str;
    }

    /**varv
     * 保存任务内容
     * @inheritdoc
     */
    public static function taskSave($title, $content, $sendee)
    {
        $connection = Yii::$app->db;
        $import_trans = $connection->beginTransaction();
        try {
            $model = new OaTask();
            $model->title = $title;
            $model->description = $content;
            $model->sendee = 1;//随意赋值，不能为空
            $model->userid = Yii::$app->user->identity->getId();
            $model->createdate = date('Y-m-d H:i:s');
            $res = $model->save();
            if (!$res) {
                throw new \Exception('任务发起失败!');
            }
            //保存接收人
            foreach ($sendee as $value) {
                $sendModel = new OaTaskSendee();
                $sendModel->userid = $value['user_id'];
                $sendModel->taskid = $model->taskid;
                $ret = $sendModel->save();
                if (!$ret) {
                    throw new \Exception('任务发起失败!');
                }
            }
            $import_trans->commit();
        } catch (\Exception $e) {
            $import_trans->rollBack();
        }
    }


}
