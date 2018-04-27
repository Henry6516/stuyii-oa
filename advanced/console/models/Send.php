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
    public static function sendEmail($name, $email, $title, $content)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject($title);
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
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
