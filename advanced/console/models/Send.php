<?php
/**
 * Created by PhpStorm.
 * User: 许先生
 * Date: 2017/9/28
 * Time: 15:32
 */

namespace console\models;

use Yii;
use yii\helpers\ArrayHelper;

class Send
{

    /**
     * 发送邮件
     * @return string
     */
    public static function sendEmail($name, $email, $num)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject("产品中心产品待审核");
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $content = '<div>'.
                        $name.'<p style=" text-indent:2em;">你好:</p>
                        <p style="text-indent:2em;">您有<span style="font-size:150%;color: red;">'.$num. '</span>个商品需要审核,请您尽快处理!
                        详情请查看:<a href="http://58.246.226.254:8010/oa-check/to-check">http://58.246.226.254:8010/oa-check/to-check</a></p></div>';
        $mail->setHtmlBody($content);    //发布可以带html标签的文本
        if($mail->send())
            echo "Mail send success to ".$name."\r\n";
        else
            echo "Mail send failed to ".$name."\r\n";
    }

    /**
     * 发送邮件记录保存
     * @return string
     */
    public static function saveEmailLog($user_id, $content, $mobile, $templeate_code = 'cpic_notice')
    {

    }

}
