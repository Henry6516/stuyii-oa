<?php
/**
 * @desc PhpStorm.
 * @author: tupure
 * @since: 2018-03-14 16:45
 */

namespace console\controllers;
use yii\console\Controller;
use console\models\Send;

/**
 * Test
 * @package console\controllers
 *
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        Send::sendEmail('james','zhoupengxu@dingtalk.com',666);
    }
}