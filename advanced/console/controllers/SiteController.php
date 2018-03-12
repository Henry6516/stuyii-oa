<?php

namespace console\controllers;


use backend\models\OaGoods;
use backend\models\User;
use console\models\Send;
use yii\console\Controller;
use Yii;
class SiteController extends Controller
{

    /**
     * 查询管理员待审核商品数并发邮件
     * 访问方法: php yii site/index parameter
     * @return mixed
     */
    public function actionIndex()
    {
        $sql = "P_oa_checkNumbers";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                Send::sendEmail($v['username'],$v['email'],$v['num']);
            }
        }
        exit;
    }


}