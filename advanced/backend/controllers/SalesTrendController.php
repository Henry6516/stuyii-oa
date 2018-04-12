<?php

namespace backend\controllers;


use backend\models\EntryForm;
use backend\models\OaEbaySuffix;
use backend\models\WishSuffixDictionary;
use common\components\BaseController;
use Yii;
use yii\helpers\ArrayHelper;

class SalesTrendController extends BaseController
{
    public function actionIndex()
    {
        $model = new EntryForm();
        //获取搜索条件
        $get = Yii::$app->request->post();
        if(isset($get['EntryForm'])){
            $data = $get['EntryForm'];
            $account = isset($get['EntryForm']['create_range']) ? $get['EntryForm']['create_range'] : [];
            $data['create_range'] = implode(',',$account);
            $order = explode(' - ', $data['order_range']);
            $data['order_start'] = $order[0];
            $data['order_end'] = $order[1];
            $model->type = $get['EntryForm']['type'];
            $model->cat = $get['EntryForm']['cat'];
            $model->order_range = $get['EntryForm']['order_range'];
            $model->create_range = $account;
        }else{
            $data['type'] = 0;
            $data['cat'] = '';
            $data['order_range'] = date('Y-m-d',strtotime("-30 day")) . ' - ' . date('Y-m-d');
            $data['create_range'] = '';
            $model->order_range = $data['order_range'];//设置开始时间初始值
            $model->create_range = $data['create_range'];//设置结束时间初始值
            $data['order_start'] = date('Y-m-d',strtotime("-30 day"));
            $data['order_end'] = date('Y-m-d');
        }
        $sql = "P_oa_AMTtrend 0,'" . $data['order_start'] . "','" . $data['order_end'] . "','" . $data['type'] . "','" .
                $data['cat'] . "','" . $data['create_range'] . "'";
        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if($ret !== false){
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql,$result,86400);
        }
        //处理数据
        if($data['type']){//按月
            $date = array_unique(ArrayHelper::getColumn($result,'mt'));
        }else{//按天
            $date = array_unique(ArrayHelper::getColumn($result,'dt'));
        }
        $date = array_values($date);
        $name = array_unique(ArrayHelper::getColumn($result,'ADDRESSOWNER'));
        sort($name);
        $arr_qty = $arr_amt = [];
        foreach ($name as $k => $v){
            $it = $item = [];
            foreach ($result as $key => $value) {
                if ($v == $value['ADDRESSOWNER']) {
                    $it[] =  empty($value['l_qty'])?0:$value['l_qty'];
                    $item[] =  empty($value['l_AMT'])?0:$value['l_AMT'];
                }
            }
            $arr_qty[] = $it;
            $arr_amt[] = $item;
        }

        //获取销量数据
        $salesData = [
            'date' => $date,
            'name' => $name,
            'value' => $arr_qty
        ];
        //获取销售额数据
        $salesVolumeData = [
            'date' => $date,
            'name' => $name,
            'value' => $arr_amt
        ];

        //获取帐号列表
        $sql = 'SELECT suffix From Y_suffixPingtai ORDER BY suffix';
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $list = ArrayHelper::map($res,'suffix','suffix');
        $accountList = array_unique($list);

        return $this->render('index', [
            'model' => $model,
            'salesData' => $salesData,
            'salesVolumeData' => $salesVolumeData,
            'accountList' => $accountList
        ]);
    }

}
