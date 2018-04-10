<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'plugins/bootbox/bootbox.min.js',
        'plugins/app.min.js',
        'plugins/layer/layer.js',
        //'js/pageSize.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * 加入js
     *
     * @param $view
     * @param $file
     * @param $position
     */
    public static function addJs($view, $file, $position = View::POS_END)
    {
        if (is_array($file)) {
            foreach ($file as $v)
                $view->registerJsFile('@web/' . $v, ['position' => $position, 'depends' => 'backend\assets\AppAsset']);
        } else {
            $view->registerJsFile('@web/' . $file, ['position' => $position, 'depends' => 'backend\assets\AppAsset']);
        }
    }

    /**
     * 加入css
     *
     * @param $view
     * @param $file
     * @param $position
     */
    public static function addCss($view, $file, $position = View::POS_HEAD)
    {
        if (is_array($file)) {
            foreach ($file as $v)
                $view->registerCssFile('@web/' . $v, ['position' => $position, 'depends' => 'backend\assets\AppAsset']);
        } else {
            $view->registerCssFile('@web/' . $file, ['position' => $position, 'depends' => 'backend\assets\AppAsset']);
        }
    }

    /**
     * 加载ueditor 富文本框
     * @param $view
     */
    public static function addEdit($view)
    {
        self::addJs($view, [
            'js/uedit/ueditor.config.js',
            'js/uedit/ueditor.all.js'
        ]);
    }



}
