<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_goods".
 *
 * @property integer $nid
 * @property integer $img
 * @property string $cate
 * @property string $devNum
 * @property string $origin1
 * @property string $hopeProfit
 * @property string $developer
 * @property string $introducer
 * @property string $devStatus
 * @property string $checkStatus
 * @property string $createDate
 * @property string $updateDate
 */
class OaForwardGoods extends GoodsCats
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      
            [['img','cate','subCate','vendor1'],'required'],
            [['origin1','origin2','origin3','vendor2','vendor3','devNum',
                'developer','introducer','introReason','devStatus','checkStatus',
                'salePrice','hopeWeight','hopeRate','hopeSale','hopeCost',
                'hopeMonthProfit','createDate','updateDate','approvalNote'],'string'],
            [['cate', 'subCate','salePrice','hopeWeight','hopeRate','hopeSale',],'safe'],
            [['stockUp'],'validateStock','skipOnEmpty' => false, 'skipOnError' => false]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => '编号',
            'img' => '图片',
            'cate' => '主类目',
            'subCate' => '子类目',
            'vendor1' => '供应商链接1',
            'vendor2' => '供应商链接2',
            'vendor3' => '供应商链接3',
            'origin1' => '平台参考链接1',
            'origin2' => '平台参考链接2',
            'origin3' => '平台参考链接3',
            'devNum' => '开发编号',
            'developer' => '开发员',
            'introducer' => '推荐人',
            'devStatus' => '认领',
            'checkStatus' => '产品状态',
            'salePrice' => '售价($)',
            'hopeCost' => '预估成本(￥)',
            'hopeWeight' => '预估重量(克)',
            'hopeRate' => '预估利润率(%)',
            'hopeSale' => '预估月销量',
            'hopeMonthProfit' => '预估月毛利($)',
            'createDate' => '创建时间',
            'updateDate' => '更新时间',
            'stockUp' => '是否备货',
        ];
    }

    public function validateStock($attribute, $params)
    {
        $user = Yii::$app->user->identity->username;
        $stockUsed = 'select count(*) as usedStock from oa_goods where stockUp=1 and developer=:developer and DATEDIFF(mm, createDate, getdate()) = 0';
        $stockHave = 'select stockNumThisMonth as haveStock  from oa_stock_goods_number where DATEDIFF(mm, createDate, getdate()) = 0 and developer=:developer';
        $connection = Yii::$app->db;
        $used = $connection->createCommand($stockUsed,[':developer'=>$user])->queryAll()[0]['usedStock'];
        $have = $connection->createCommand($stockHave,[':developer'=>$user])->queryAll()[0]['haveStock'];
        if($used>$have) {
            $this->addError($attribute, '超出本月备货数量！');
        }
    }


}
