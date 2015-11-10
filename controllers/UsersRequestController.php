<?php

namespace app\controllers;

use Yii;
use app\models\UsersRequest;
use app\models\UsersRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersRequestController implements the CRUD actions for UsersRequest model.
 */
class UsersRequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all UsersRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersRequestSearch();
        $user_id = "";
        if(isset(Yii::$app->user)){
           $user_id = Yii::$app->user->getId();
        }

        $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
        $queryParams["UsersRequestSearch"]["user_id"] = $user_id;
        $dataProvider = $searchModel->search($queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsersRequest model.
     * @param string $request_id
     * @param string $user_id
     * @return mixed
     */
    public function actionView($request_id, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($request_id, $user_id),
        ]);
    }

    /**
     * Creates a new UsersRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsersRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'request_id' => $model->request_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UsersRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $request_id
     * @param string $user_id
     * @return mixed
     */
    public function actionUpdate($request_id, $user_id)
    {
        $model = $this->findModel($request_id, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'request_id' => $model->request_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UsersRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $request_id
     * @param string $user_id
     * @return mixed
     */
    public function actionDelete($request_id, $user_id)
    {
        $this->findModel($request_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UsersRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $request_id
     * @param string $user_id
     * @return UsersRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($request_id, $user_id)
    {
        if (($model = UsersRequest::findOne(['request_id' => $request_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
