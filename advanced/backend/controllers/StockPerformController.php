<?php

namespace backend\controllers;

use backend\models\EntryForm;
use common\components\BaseController;
use Yii;
use yii\data\ArrayDataProvider;

class StockPerformController extends BaseController
{
    /**
     * 库存数量
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $model = new EntryForm();
        //获取开发员列表
        $devList = $this->getDevList();
        //获取搜索条件
        $get = Yii::$app->request->get();
        if(isset($get['EntryForm'])){
            $create_range = $get['EntryForm']['create_range'];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->cat = $data['cat'] = $get['EntryForm']['cat'];
            $model->code = $data['code'] = $get['EntryForm']['code'];
            $model->create_range = $create_range;
        }else{
            $data['cat'] = '';
            $data['create_start'] = '';
            $data['create_end'] = '';
            $data['code'] = '';
        }
        //获取数据
        $sql = "P_oa_StockProduct '" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat'] . "','".$data['code'] . "'";

        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if($ret !== false){
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql,$result,3600*24);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => isset($get['pageSize']) && $get['pageSize'] ? $get['pageSize'] : 20,
            ],
            'sort' => [
                'attributes' => ['Number', 'Money', 'SellCount1', 'SellCount2', 'SellCount3'],
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'list' => $devList,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 订货产品表现
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionStock()
    {
        $model = new EntryForm();
        //获取开发员列表
        $devList = $this->getDevList();
        //获取搜索条件
        $get = Yii::$app->request->get();
        if(isset($get['EntryForm'])){
            $create_range = $get['EntryForm']['create_range'];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->cat = $data['cat'] = $get['EntryForm']['cat'];
            $model->create_range = $create_range;
        }else{
            $data['cat'] = '';
            $data['create_start'] = date('Y-m-d',strtotime('-75 days'));
            $data['create_end'] = date('Y-m-d',strtotime('-15 days'));
            $create_range = $data['create_start'] . ' - ' . $data['create_end'];
            $model->cat = $data['cat'];
            $model->create_range = $create_range;
        }
        //获取数据
        $sql = "P_oa_StockPerformance '" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat'] . "'";

        //缓存数据
//        $cache = Yii::$app->local_cache;
//        $ret = $cache->get($sql);
//        if($ret !== false){
//            $result = $ret;
//        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
//            $cache->set($sql,$result,3600*24);
//        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => isset($get['pageSize']) && $get['pageSize'] ? $get['pageSize'] : 20,
            ],
            'sort' => [
                'attributes' => ['Number', 'orderNum', 'orderRate', 'hotStyleNum', 'hotStyleRate', 'exuStyleNum', 'exuStyleRate','stockNumThisMonth','stockNumLastMonth'],
            ],
        ]);

        return $this->render('stock', [
            'model' => $model,
            'list' => $devList,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 非订货产品表现
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionNonstock()
    {
        $model = new EntryForm();
        //获取开发员列表
        $devList = $this->getDevList();
        //获取搜索条件
        $get = Yii::$app->request->get();
        if(isset($get['EntryForm'])){
            $create_range = $get['EntryForm']['create_range'];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->cat = $data['cat'] = $get['EntryForm']['cat'];
            $model->create_range = $create_range;
        }else{
            $data['cat'] = '';
            $data['create_start'] = date('Y-m-d',strtotime('-75 days'));
            $data['create_end'] = date('Y-m-d',strtotime('-15 days'));
            $create_range = $data['create_start'] . ' - ' . $data['create_end'];
            $model->cat = $data['cat'];
            $model->create_range = $create_range;
        }
        //获取数据
        $sql = "P_oa_Non_StockPerformance '" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat'] . "'";
        //var_dump($sql);exit;
        //缓存数据
//        $cache = Yii::$app->local_cache;
//        $ret = $cache->get($sql);
//        if($ret !== false){
//            $result = $ret;
//        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
//            $cache->set($sql,$result,3600*24);
//        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => isset($get['pageSize']) && $get['pageSize'] ? $get['pageSize'] : 20,
            ],
            'sort' => [
                'attributes' => ['Number', 'orderNum', 'orderRate', 'hotStyleNum', 'hotStyleRate', 'exuStyleNum', 'exuStyleRate','stockNumThisMonth','stockNumLastMonth'],
            ],
        ]);

        return $this->render('nonstock', [
            'model' => $model,
            'list' => $devList,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 销售人员对应销售的产品表现（包括备货和不备货）
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionSales()
    {
        $model = new EntryForm();
        //获取销售员列表
        $salesList = $this->getSalesList();
        $sales = implode(',',$salesList);
        //获取搜索条件
        $get = Yii::$app->request->get();
        if(isset($get['EntryForm'])){
            $create_range = $get['EntryForm']['create_range'];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->code = $data['code'] = $get['EntryForm']['code'];
            $model->cat = $data['cat'] = $get['EntryForm']['cat'];
            $model->create_range = $create_range;
        }else{
            $data['cat'] = $sales;
            $data['create_start'] = date('Y-m-d',strtotime('-75 days'));
            $data['create_end'] = date('Y-m-d', strtotime('-15 days'));
            $create_range = $data['create_start'] . ' - ' . $data['create_end'];
            $model->code = $data['code'] = '';
            $model->cat = $data['cat'];
            $model->create_range = $create_range;
        }

        //获取数据
        $sql = "P_oa_sales_Performance '" . $data['code'] . "','" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat'] . "'";
        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
//        if($ret !== false){
//            $result = $ret;
//        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
//            $cache->set($sql,$result,3600*24);
//        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => isset($get['pageSize']) && $get['pageSize'] ? $get['pageSize'] : 20,
            ],
            'sort' => [
                'attributes' => ['Number', 'orderNum', 'orderRate', 'exuStyleNum', 'exuStyleRate', 'hotStyleNum', 'hotStyleRate'],
            ],
        ]);

        return $this->render('sales', [
            'model' => $model,
            'list' => $salesList,
            'dataProvider' => $dataProvider,
        ]);

    }


    /**
     * 根据登录人员身份获取开发员列表
     */
    public function getDevList(){
        $username = Yii::$app->user->identity->username;
        $res = Yii::$app->db->createCommand("select department,leaderName,isLeader,groupMaster,isGroupMaster from [user] WHERE username='$username'")->queryOne();
        $role = "'产品开发','产品开发2'";
        if($res['isLeader']){
            $manger_username =$res['leaderName'];
            if($username == '宋现中' || $username == '尚显贝'){
                $role = "'产品开发','产品开发','部门主管'";
            }
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'' AND u.leaderName='$manger_username'";
        }elseif($res['isGroupMaster']){
            $master_username =$res['groupMaster'];
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'' AND u.groupMaster='$master_username'";
        }elseif($username=='admin'){
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE (aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'') OR 
                (aa.item_name='部门主管' AND ISNULL(u.username,'') IN ('宋现中','尚显贝'))";
        }else{
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')='$username'";
        }
        $developer= Yii::$app->db->createCommand($sql)->queryAll();
        $dev=[];
        foreach($developer as $v){
            $dev[$v['username']]  = $v['username'];
        }
        return array_unique($dev);
    }


    /**
     * 根据登录人员身份获取销售员列表
     */
    public function getSalesList(){
        $username = Yii::$app->user->identity->username;
        $res = Yii::$app->db->createCommand("select department,leaderName,isLeader,groupMaster,isGroupMaster from [user] WHERE username='$username'")->queryOne();
        $role = "'Wish销售','SMT销售','Joom销售','eBay销售'";
        if($res['isLeader']){
            $manger_username =$res['leaderName'];
            if($username == '宋现中' || $username == '尚显贝'){
                $role .= ",'部门主管'";
            }
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'' AND u.leaderName='$manger_username'";
        }elseif($res['isGroupMaster']){
            $master_username =$res['groupMaster'];
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'' AND u.groupMaster='$master_username'";
        }elseif($username=='admin'){
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE (aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')<>'') OR 
                (aa.item_name='部门主管' AND ISNULL(u.username,'') IN ('宋现中','尚显贝'))";
        }else{
            $sql ="select DISTINCT u.username from auth_assignment aa
                LEFT JOIN [user] u on u.id=aa.user_id
                WHERE aa.item_name IN (" . $role . ") AND ISNULL(u.username,'')='$username'";
        }
        $developer= Yii::$app->db->createCommand($sql)->queryAll();
        foreach($developer as $v){
            $dev[$v['username']]  = $v['username'];
        }
        return array_unique($dev);
    }
}
