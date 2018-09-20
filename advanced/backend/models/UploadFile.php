<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-08-06 11:34
 */
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;



class UploadFile extends Model
{
    /**
     * @var UploadedFile
     */

    public $excelFile;

    public function rules()
    {
        return [
            //ToDO validate excel file
//            [
//                ['excelFile'], 'file','skipOnEmpty' => false,
//                'extensions' => 'xls, xlsx, xlsm'
//            ],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::$app->basePath . '/uploads/';
            if (!file_exists($path)) {
                !is_dir($path) && !mkdir($path, 0777) && is_dir($path);
            }
            $pathName = $path.time().$this->excelFile->extension;
            $pathName = iconv('UTF-8','GB2312',$pathName);
            $this->excelFile->saveAs($pathName);
            return $pathName;
        }
        return false;
    }

    public function uploadImg()
    {
        if ($this->validate()) {
            $file_name = date("YmdHis",time()).rand(1000,9999).'.'.$this->excelFile->extension;
            $savePath = '/uploads/images/'.date("Ymd",time());
            $model_path = Yii::$app->basePath.'/web/uploads/images/';
            $path = Yii::$app->basePath.'/web/'.$savePath.'/';
            if (!file_exists($model_path)) mkdir($model_path,0777);
            if (!file_exists($path)) mkdir($path,0777);
            $this->excelFile->saveAs($path.$file_name);
            return $savePath.'/'.$file_name;
        }
        return false;
    }

}