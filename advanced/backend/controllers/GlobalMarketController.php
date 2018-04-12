<?php

namespace backend\controllers;

use backend\models\EntryForm;
use common\components\BaseController;
use Yii;
use yii\helpers\ArrayHelper;

class GlobalMarketController extends BaseController
{
    /**
     * 订货产品表现
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $model = new EntryForm();
        //获取搜索条件
        $get = Yii::$app->request->get();
        //var_dump($get);exit;
        if (isset($get['EntryForm'])) {
            $order_range = $get['EntryForm']['order_range'];
            $order = explode(' - ', $order_range);
            $data['order_start'] = (!empty($order[0])) ? $order[0] : '';
            $data['order_end'] = (!empty($order[1])) ? $order[1] : '';
            $model->cat = $data['cat'] = $get['EntryForm']['cat'];
            $model->code = $data['code'] = $get['EntryForm']['code'];
            $model->create_range = $data['create_range'] = $get['EntryForm']['create_range'];
            $model->order_range = $order_range;
        } else {
            $data['cat'] = '';//平台
            $data['order_start'] = date('Y-m-d',strtotime("-30 day"));
            $data['order_end'] = date('Y-m-d');
            $data['create_range'] = '';//账号
            $data['code'] = '';//商品编码
            $model->order_range = $data['order_start'].' - '.$data['order_end'];//设置时间初始值
        }
        //获取数据
        $sql = "P_oa_GlobalMarketAnalysis 0,'" . $data['order_start'] . "','" . $data['order_end'] . "','".$data['code'] . "','".$data['cat'] . "','".$data['create_range']. "'";

        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if ($ret !== false) {
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql, $result, 2592000);
        }
        //var_dump($result);exit;
        //获取销量和销售额图表数据
        if($result){
            $sale['name'] = $saleNum['name'] = ArrayHelper::getColumn($result,'CountryName');
            $arr1 = $arr2 = [];
            foreach ($result as $k => $v){
                $arr1[$k] = ['name' => $v['CountryName'], 'value' => $v['l_qty']];
                $arr2[$k] = ['name' => $v['CountryName'], 'value' => $v['l_AMT']];
            }
            $saleNum['data'] = $arr1;
            $saleNum['maxValue'] = max(ArrayHelper::getColumn($result,'l_qty'));
            $sale['data'] = $arr2;
            $sale['maxValue'] = max(ArrayHelper::getColumn($result,'l_AMT'));
        }else{
            $sale['name'] = $saleNum['name'] = [];
            $saleNum['data'] = $sale['data'] = [];
            $saleNum['maxValue'] = $sale['maxValue'] = 100;
        }


        //获取平台列表(CategoryID=12 是卖家简称)
        $platSql = "SELECT FitCode 
                FROM [dbo].[B_Dictionary] WHERE CategoryID=12 AND ISNULL(FitCode,'')<>''
                GROUP BY FitCode ORDER BY FitCode ASC";
        $platList = Yii::$app->db->createCommand($platSql)->queryAll();
        $platList = ArrayHelper::map($platList, 'FitCode', 'FitCode');
        //获取帐号列表
        $accountSql = 'SELECT suffix From Y_suffixPingtai ORDER BY suffix';
        $res = Yii::$app->db->createCommand($accountSql)->queryAll();
        $list = ArrayHelper::map($res, 'suffix', 'suffix');
        $accountList = array_unique($list);
        return $this->render('index', [
            'model' => $model,
            'list' => $platList,
            'accountList' => $accountList,
            'sale' => $sale,
            'saleNum' => $saleNum,
        ]);
    }

}
