<?php

namespace backend\controllers;

use backend\models\EntryForm;
use backend\models\OaGoodsinfo;
use Yii;
use yii\helpers\ArrayHelper;

class DevPerformController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new EntryForm();
        //获取搜索条件
        $get = Yii::$app->request->get();
        if(isset($get['EntryForm'])){
            $data['type'] = $get['EntryForm']['type'];
            $order_range = $get['EntryForm']['order_range'];
            $create_range = $get['EntryForm']['create_range'];
            $order = explode(' - ', $order_range);
            $data['order_start'] = $order[0];
            $data['order_end'] = $order[1];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->type = $get['EntryForm']['type'];
            $model->order_range = $order_range;
            $model->create_range = $create_range;
        }else{
            $data['type'] = 0;
            //$data['cat'] = '';
            $data['order_start'] = date('Y-m-d',strtotime("-30 day"));
            $data['order_end'] = date('Y-m-d');
            $data['create_start'] = '';
            $data['create_end'] = '';
            $model->order_range = $data['order_start'].' - '.$data['order_end'];//设置时间初始值
        }
        $sql = "P_oa_DeveloperPerformance " . $data['type'] . " ,'" . $data['order_start'] . "','" . $data['order_end'] . "','" . $data['create_start'] . "','" . $data['create_end'] ."'";
        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if($ret !== false){
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql,$result,86400);
        }
        //获取销量和销售额图表数据
        $sale['name'] = $saleNum['name'] = ArrayHelper::getColumn($result,'SalerName');
        $arr1 = $arr2 = [];
        foreach ($result as $k => $v){
            $arr1[$k] = ['name' => $v['SalerName'], 'value' => $v['codeNum']];
            $arr2[$k] = ['name' => $v['SalerName'], 'value' => $v['l_AMT']];
        }
        $saleNum['data'] = $arr1;
        $saleNum['maxValue'] = max(ArrayHelper::getColumn($result,'codeNum'));
        $sale['data'] = $arr2;
        $sale['maxValue'] = max(ArrayHelper::getColumn($result,'l_AMT'));

        //获取开发员开发产品款数(不受订单影响)
        $numSql = 'SELECT SalerName AS name,count(GoodsCode) AS value FROM B_Goods b 
                    LEFT JOIN [user] u ON u.username=b.SalerName
                    WHERE u.username<>\'\' ';
        if($data['create_start'] && $data['create_end']){
            $numSql .= " AND CreateDate BETWEEN '" . $data['create_start'] . "' AND '" . $data['create_end'] . "'";
        }
        $numSql .= ' GROUP BY SalerName';
        $result = Yii::$app->db->createCommand($numSql)->queryAll();
        $saleNum['data'] = $result;
        $saleNum['name'] = ArrayHelper::getColumn($result,'name');
        $saleNum['maxValue'] = max(ArrayHelper::getColumn($result,'value'));

        //var_dump($saleNum);exit;
        return $this->render('index',[
            'model' => $model,
            'sale' => $sale,
            'saleNum' => $saleNum,
        ]);
    }



}
