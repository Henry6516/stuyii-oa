<?php

namespace backend\controllers;

use common\components\BaseController;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('index', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     *  create new user
     */
    public function actionSignup ()
    {
        // 实例化一个表单模型，这个表单模型我们还没有创建，等一下后面再创建
        $model = new \backend\models\SignupForm();

        // 下面这一段if是我们刚刚分析的第二个小问题的实现，下面让我具体的给你描述一下这几个方法的含义吧
        // $model->load() 方法，实质是把post过来的数据赋值给model的属性
        // $model->signup() 方法, 是我们要实现的具体的添加用户操作
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            // 添加完用户之后，我们跳回到index操作即列表页
            return $this->redirect(['index']);
        }

        // 下面这一段是我们刚刚分析的第一个小问题的实现
        // 渲染添加新用户的表单
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->store = $model->store?explode(',',$model->store):[];
        $model->mapPersons = $model->mapPersons?explode(',',$model->mapPersons):[];
        $store = $this->getStore();
        $person = $this->getPerson();
        $request = Yii::$app->request;
        if($request->isPost){
            $post = $request->post();
            $user = $post['User'];
            $stores = $user['store']?implode(',',$user['store']):'';
            $post['User']['store'] = $stores;
            $person = $user['mapPersons']?implode(',',$user['mapPersons']):'';
            $post['User']['mapPersons'] = $person;
            if ($model->load($post) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'store' => $store,
                'person' => $person
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    private function getStore()
    {
        $db = Yii::$app->db;
        $sql = 'SELECT StoreName as store from B_store';
        $query = $db->createCommand($sql)->queryAll();
        $ret = ArrayHelper::getColumn($query,'store');
        return \array_combine($ret,$ret);
    }

    private function getPerson()
    {
        $db = Yii::$app->db;
        $sql = "select ur.username as person from [user] as ur LEFT JOIN auth_assignment as ag on ur.id = ag.user_id where ag.item_name like '%开发%' or ag.item_name like '%销售%'";
        $query = $db->createCommand($sql)->queryAll();
        $ret = ArrayHelper::getColumn($query,'person');
        return \array_combine($ret,$ret);
    }

}
