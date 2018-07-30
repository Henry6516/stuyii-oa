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
        $title = "产品中心产品待审核";
        $sql = "P_oa_checkNumbers";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                $content = '<div>'.
                    $v['username'].'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有<span style="font-size:150%;color: red;">'.$v['num']. '</span>个商品需要审核,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-check/to-check">http://58.246.226.254:8010/oa-check/to-check</a></p></div>';
                Send::sendEmail($v['username'],$v['email'],$title,$content);
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
            echo date('Y-m-d H:i:s')." 更新成功！\n";
        }else{
            echo date('Y-m-d H:i:s')."更新失败！\n";
        }
    }

    /**
     * 获取Wish待刊登产品数量并发邮件给开发员
     * 访问方法: php yii site/publish parameter
     * @return mixed
     */
    public function actionPublish()
    {
        $title = "数据中心产品Wish刊登";
        $sql = "SELECT developer,count(goodscode) AS num,email FROM oa_goodsinfo oa
                INNER JOIN [user] u ON u.username=oa.developer WHERE wishpublish='Y' GROUP BY developer,email";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        //var_dump($leader);exit;
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                $content = '<div>'.
                    $v['developer'].'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有<span style="font-size:150%;color: red;">'.$v['num']. '</span>个商品需要进行Wish刊登,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-data-center/index">http://58.246.226.254:8010/oa-data-center/index</a></p></div>';
                Send::sendEmail($v['developer'],$v['email'],$title,$content);
            }
        }
        exit;
    }

    /**
     * 备货产品表现
     * 每月第一天（1号）更新开发员在本月的可用备货数量
     * 访问方法: php yii site/stock
     * @return mixed
     */
    public function actionStock()
    {
        $end = date('Y-m-d');
        //$end = '2018-07-01';
        if(substr($end,8,2) !== '01'){
            echo date('Y-m-d H:i:s')." 当前时间不可更新该项数据，请于管理员联系确认！";exit();
        }
        $start = date('Y-m-d',strtotime('-60 days', strtotime($end)));

        //获取数据库数据，查看是否已存在数据
        $checkSql = "SELECT * FROM oa_stock_goods_number 
                    WHERE CONVERT(VARCHAR(10),createDate,121)=CONVERT(VARCHAR(10),CAST((CONVERT(VARCHAR(7),'{$end}',121)+'-01') AS DATETIME),121)
                    AND isStock='stock'";
        $check = Yii::$app->db->createCommand($checkSql)->queryAll();
        if($check){
            echo date('Y-m-d H:i:s')." 本月可用备货数量已经更新，请勿重复操作！\n";
        }else{
            $sql = "EXEC P_oa_StockPerformance '" . $start . "','" . $end . "','',1";
            $list = Yii::$app->db->createCommand($sql)->queryAll();
            $res = Yii::$app->db->createCommand()->batchInsert(
                'oa_stock_goods_number',
                ['developer','Number','orderNum','hotStyleNum','exuStyleNum','rate1','rate2','stockNumThisMonth','stockNumLastMonth','createDate','isStock'],
                $list
            )->execute();
            if($res){
                echo date('Y-m-d H:i:s')." 开发员的可用备货数量更新成功！\n";
            }else{
                echo date('Y-m-d H:i:s')." 开发员的可用备货数量更新成功！\n";
            }
        }

    }


    /**
     * 不备货产品表现
     * 每月第一天（1号）更新开发员在本月的可用备货数量
     * 访问方法: php yii site/nonstock
     * @return mixed
     */
    public function actionNonstock()
    {
        $end = date('Y-m-d');
        //$end = date('2018-07-01');
        if(substr($end,8,2) !== '01'){
            echo date('Y-m-d H:i:s')." 当前时间不可更新该项数据，请于管理员联系确认！";exit();
        }
        $start = date('Y-m-d',strtotime('-60 days', strtotime($end)));

        //获取数据库数据，查看是否已存在数据
        $checkSql = "SELECT * FROM oa_stock_goods_number 
                    WHERE CONVERT(VARCHAR(10),createDate,121)=CONVERT(VARCHAR(10),CAST((CONVERT(VARCHAR(7),'{$end}',121)+'-01') AS DATETIME),121)
                    AND isStock='nonstock'";
        $check = Yii::$app->db->createCommand($checkSql)->queryAll();
        if($check){
            echo date('Y-m-d H:i:s')." 本月不备货产品可用数量已经更新，请勿重复操作！\n";
        }else{
            $sql = "EXEC P_oa_Non_StockPerformance '" . $start . "','" . $end . "','',1";
            $list = Yii::$app->db->createCommand($sql)->queryAll();
            $res = Yii::$app->db->createCommand()->batchInsert(
                'oa_stock_goods_number',
                ['developer','Number','orderNum','hotStyleNum','exuStyleNum','rate1','rate2','stockNumThisMonth','stockNumLastMonth','createDate','isStock'],
                $list
            )->execute();
            if($res){
                echo date('Y-m-d H:i:s')." 开发员的不备货产品可用数量更新成功！\n";
            }else{
                echo date('Y-m-d H:i:s')." 开发员的不备货产品可用数量更新成功！\n";
            }
        }

    }
}