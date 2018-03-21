<?php

namespace backend\controllers;

use backend\models\EntryForm;
use Yii;
use yii\data\ArrayDataProvider;

class StockPerformController extends \yii\web\Controller
{
    /**
     * 订货产品表现
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
        //var_dump($get);exit;
        if(isset($get['EntryForm'])){
            $data['cat'] = $get['EntryForm']['cat'];
            $order_range = $get['EntryForm']['order_range'];
            $create_range = $get['EntryForm']['create_range'];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->cat = $get['EntryForm']['cat'];
            $model->order_range = $order_range;
            $model->create_range = $create_range;
        }else{
            $data['cat'] = '';
            $data['create_start'] = '';
            $data['create_end'] = '';
            $model->order_range = $data['order_range'] = '';
        }
        //获取数据
        //var_dump($data);exit;
        //$sql = "P_oa_ProductPerformance 0" . " ,'" . $data['create_start'] . "','" . $data['create_end'] . "','" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat']."'";

        $sql = "P_oa_StockPerformance '" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat'] . "','".$data['order_range'] . "'";

        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if($ret !== false){
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql,$result,2592000);
        }
        //$result = Yii::$app->db->createCommand($sql)->queryAll();
        //var_dump($result);exit;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['l_AMT', 'l_qty'],
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'list' => $devList,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 库存数量
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
        var_dump($get);exit;
        if(isset($get['EntryForm'])){
            $data['type'] = $get['EntryForm']['type'];
            $data['cat'] = $get['EntryForm']['cat'];
            $order_range = $get['EntryForm']['order_range'];
            $create_range = $get['EntryForm']['create_range'];
            $order = explode(' - ', $order_range);
            $data['order_start'] = $order[0];
            $data['order_end'] = $order[1];
            $create = explode(' - ', $create_range);
            $data['create_start'] = (!empty($create[0])) ? $create[0] : '';
            $data['create_end'] = (!empty($create[1])) ? $create[1] : '';
            $model->type = $get['EntryForm']['type'];
            $model->cat = $get['EntryForm']['cat'];
            $model->order_range = $order_range;
            $model->create_range = $create_range;
        }else{
            $data['type'] = 0;
            $data['cat'] = '';
            $data['order_start'] = date('Y-m-d',strtotime("-30 day"));
            $data['order_end'] = date('Y-m-d');
            $data['create_start'] = '';
            $data['create_end'] = '';
            $model->order_range = $data['order_start'].' - '.$data['order_end'];//设置时间初始值
        }
        //var_dump($data);exit;
        //获取数据
        $sql = "P_oa_ProductPerformance " . $data['type'] . " ,'" . $data['order_start'] . "','" . $data['order_end'] . "','" . $data['create_start'] . "','" . $data['create_end'] . "','".$data['cat']."'";
//        P_oa_CategoryPerformance_demo 0 ,'2018-01-01','2018-01-23','',''

        //缓存数据
        $cache = Yii::$app->local_cache;
        $ret = $cache->get($sql);
        if($ret !== false){
            $result = $ret;
        } else {
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $cache->set($sql,$result,2592000);
        }
        //$result = Yii::$app->db->createCommand($sql)->queryAll();
        var_dump($result);exit;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['l_AMT', 'l_qty'],
            ],
        ]);

        return $this->render('index', [
            'model' => $model,
            'list' => $devList,
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
        foreach($developer as $v){
            $dev[$v['username']]  = $v['username'];
        }
        return array_unique($dev);
    }
}
