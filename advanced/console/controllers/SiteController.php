<?php

namespace console\controllers;


use backend\models\OaGoods;
use backend\models\OaGoodsinfo;
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
                $content = '<div>'.
                    $v['username'].'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有<span style="font-size:150%;color: red;">'.$v['num']. '</span>个商品需要审核,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-check/to-check">http://58.246.226.254:8010/oa-check/to-check</a></p></div>';
                Send::sendEmail($v['username'],$v['email'],$content);
            }
        }
        exit;
    }

    /**
     * 查询wish平台商品状态、采购到货天数并更新oa_goodsinfo表数据
     * 访问方法: php yii site/wish parameter
     * @return mixed
     */
    public function actionWish()
    {
        $sql = "P_oa_updateGoodsStatusToTableOaGoodsInfo";
        $res = Yii::$app->db->createCommand($sql)->execute();
        if($res){
            echo '更新成功！';
        }else{
            echo '更新失败！';
        }
    }

    /**
     * 获取Wish待刊登产品数量并发邮件给开发员
     * 访问方法: php yii site/publish parameter
     * @return mixed
     */
    public function actionPublish()
    {
        $sql = "SELECT developer,count(goodscode) AS num,email FROM oa_goodsinfo oa
                LEFT JOIN [user] u ON u.username=oa.developer WHERE wishpublish='Y' GROUP BY developer,email";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        //var_dump($leader);exit;
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                $content = '<div>'.
                    $v['developer'].'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有<span style="font-size:150%;color: red;">'.$v['num']. '</span>个商品需要进行Wish刊登,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-data-center/index">http://58.246.226.254:8010/oa-data-center/index</a></p></div>';
                Send::sendEmail($v['developer'],$v['email'],$content);
            }
        }
        exit;
    }

}