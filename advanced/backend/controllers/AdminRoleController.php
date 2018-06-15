<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\AuthItemSearch;
use backend\models\AuthItem;
use backend\models\AuthAdminRole;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class AdminRoleController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($role)
    {
        $model = AuthAdminRole::find()->where(['role' => $role])->one();
        if(Yii::$app->request->isPost){
            $roleInfo = Yii::$app->request->post()['AuthAdminRole'];
            $stores = $roleInfo['store']?implode(',',$roleInfo['store']):'';
            $plats = $roleInfo['plat']?implode(',',$roleInfo['plat']):'';
            $model->setAttribute('store',$stores);
            $model->setAttribute('plat',$plats);
            if($model->save()){
                return $this->redirect('update?role='.$role);
            }
        }
        else {
            $plats = $this->getPlat();
            $stores = $this->getStore();
            $model->store = $model->store?explode(',',$model->store):[];
            $model->plat = $model->plat?explode(',',$model->plat):[];
            return $this->render('update',[
                'model' => $model,
                'plats' => $plats,
                'stores' => $stores
            ]);
        }
    }

    public function actionView()
    {
        return $this->render('view');
    }

    private function getPlat()
    {
        $db = Yii::$app->db;
        $sql = 'select DictionaryName as plat from B_Dictionary where CategoryID=8 AND used=0';
        $query = $db->createCommand($sql)->queryAll();
        $ret = ArrayHelper::getColumn($query,'plat');
        return \array_combine($ret, $ret);


    }


    private function getStore()
    {
        $db = Yii::$app->db;
        $sql = 'SELECT StoreName as store from B_store';
        $query = $db->createCommand($sql)->queryAll();
        $ret = ArrayHelper::getColumn($query,'store');
        return \array_combine($ret,$ret);
    }
}
