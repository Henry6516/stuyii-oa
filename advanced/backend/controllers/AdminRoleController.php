<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\AuthItemSearch;
use backend\models\AuthItem;


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

    public function actionUpdate()
    {
        $model = new AuthItem();
        return $this->render('update',[
            'model' => $model
        ]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
