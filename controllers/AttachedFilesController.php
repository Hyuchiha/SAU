<?php

namespace app\controllers;

use Yii;
use app\models\AttachedFiles;
use app\models\AttachedFilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AttachedFilesController implements the CRUD actions for AttachedFiles model.
 */
class AttachedFilesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AttachedFiles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttachedFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AttachedFiles model.
     * @param string $request_id
     * @param string $url
     * @return mixed
     */
    public function actionView($request_id, $url)
    {
        return $this->render('view', [
            'model' => $this->findModel($request_id, $url),
        ]);
    }

    /**
     * Creates a new AttachedFiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AttachedFiles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'request_id' => $model->request_id, 'url' => $model->url]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AttachedFiles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $request_id
     * @param string $url
     * @return mixed
     */
    public function actionUpdate($request_id, $url)
    {
        $model = $this->findModel($request_id, $url);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'request_id' => $model->request_id, 'url' => $model->url]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AttachedFiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $request_id
     * @param string $url
     * @return mixed
     */
    public function actionDelete($request_id, $url)
    {
        $this->findModel($request_id, $url)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AttachedFiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $request_id
     * @param string $url
     * @return AttachedFiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($request_id, $url)
    {
        if (($model = AttachedFiles::findOne(['request_id' => $request_id, 'url' => $url])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
