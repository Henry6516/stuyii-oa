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
     * ��ѯ����Ա�������Ʒ�������ʼ�
     * ���ʷ���: php yii site/index parameter
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "��Ʒ���Ĳ�Ʒ�����";
        $sql = "P_oa_checkNumbers";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                $content = '<div>'.
                    $v['username'].'<p style=" text-indent:2em;">���:</p>
                        <p style="text-indent:2em;">����<span style="font-size:150%;color: red;">'.$v['num']. '</span>����Ʒ��Ҫ���,�������촦��!
                        ������鿴:<a href="http://58.246.226.254:8010/oa-check/to-check">http://58.246.226.254:8010/oa-check/to-check</a></p></div>';
                Send::sendEmail($v['username'],$v['email'],$title,$content);
            }
        }
        exit;
    }

    /**
     * ��ѯwishƽ̨��Ʒ״̬���ɹ���������������oa_goodsinfo������
     * ���ʷ���: php yii site/wish parameter
     * @return mixed
     */
    public function actionWish()
    {
        $sql = "P_oa_updateGoodsStatusToTableOaGoodsInfo";
        $res = Yii::$app->db->createCommand($sql)->execute();
        if($res){
            echo date('Y-m-d H:i:s')." ���³ɹ���\n";
        }else{
            echo date('Y-m-d H:i:s')."����ʧ�ܣ�\n";
        }
    }

    /**
     * ��ȡWish�����ǲ�Ʒ���������ʼ�������Ա
     * ���ʷ���: php yii site/publish parameter
     * @return mixed
     */
    public function actionPublish()
    {
        $title = "�������Ĳ�ƷWish����";
        $sql = "SELECT developer,count(goodscode) AS num,email FROM oa_goodsinfo oa
                LEFT JOIN [user] u ON u.username=oa.developer WHERE wishpublish='Y' GROUP BY developer,email";
        $leader = Yii::$app->db->createCommand($sql)->queryAll();
        //var_dump($leader);exit;
        foreach ($leader as $v){
            if($v['email'] && $v['num']){
                $content = '<div>'.
                    $v['developer'].'<p style=" text-indent:2em;">���:</p>
                        <p style="text-indent:2em;">����<span style="font-size:150%;color: red;">'.$v['num']. '</span>����Ʒ��Ҫ����Wish����,�������촦��!
                        ������鿴:<a href="http://58.246.226.254:8010/oa-data-center/index">http://58.246.226.254:8010/oa-data-center/index</a></p></div>';
                Send::sendEmail($v['developer'],$v['email'],$title,$content);
            }
        }
        exit;
    }

    /**
     * ������Ʒ����
     * ÿ�µ�һ�죨1�ţ����¿���Ա�ڱ��µĿ��ñ�������
     * ���ʷ���: php yii site/stock
     * @return mixed
     */
    public function actionStock()
    {
        $end = date('Y-m-d');
        if(substr($end,8,2) !== '01'){
            echo date('Y-m-d H:i:s')." ��ǰʱ�䲻�ɸ��¸������ݣ����ڹ���Ա��ϵȷ�ϣ�";exit();
        }
        $start = date('Y-m-d',strtotime('-60 days', strtotime($end)));

        //��ȡ���ݿ����ݣ��鿴�Ƿ��Ѵ�������
        $checkSql = "SELECT * FROM oa_stock_goods_number 
                    WHERE CONVERT(VARCHAR(10),createDate,121)=CONVERT(VARCHAR(10),CAST((CONVERT(VARCHAR(7),'{$end}',121)+'-01') AS DATETIME),121)";
        $check = Yii::$app->db->createCommand($checkSql)->queryAll();
        if($check){
            echo date('Y-m-d H:i:s')." ���¿��ñ��������Ѿ����£������ظ�������\n";
        }else{
            $sql = "EXEC P_oa_StockPerformance '" . $start . "','" . $end . "','',1";
            $list = Yii::$app->db->createCommand($sql)->queryAll();
            $res = Yii::$app->db->createCommand()->batchInsert(
                'oa_stock_goods_number',
                ['developer','Number','orderNum','hotStyleNum','exuStyleNum','rate1','rate2','stockNumThisMonth','stockNumLastMonth','createDate'],
                $list
            )->execute();
            if($res){
                echo date('Y-m-d H:i:s')." ����Ա�Ŀ��ñ����������³ɹ���\n";
            }else{
                echo date('Y-m-d H:i:s')." ����Ա�Ŀ��ñ����������³ɹ���\n";
            }
        }

    }



}