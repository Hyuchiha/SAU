<?php

namespace app\controllers;

use Yii;
use app\models\Request;
use app\models\AreasRequest;
use app\models\RequestSearch;
use app\models\AttachedFiles;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
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
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = new Request();
		$areasRequest = new AreasRequest();
		//$attachedFiles = new AttachedFiles();

        if ($request->load(Yii::$app->request->post())) {
			$request->requestFile = UploadedFile::getInstances($request, 'requestFile');
			
			$valid = true;
			$valid = $valid && $request->validate();
			if($valid){
				if($request->save() && $request->upload()){
					$areasRequest->request_id = $request->id;
					$areasRequest->area_id = $request->area_id;
					
					//$attachedFiles->request_id = $request->id;
					//$attachedFiles->url = $request->fileNameAttached;
					if($areasRequest->save()){
					//$valid = $valid && $attachedFiles->validate();
						//if($valid && $attachedFiles->save()){
						if($valid){
							return $this->redirect(['view', 'id' => $request->id]);
						//}
						}
						else{
						return $this->render('create', ['request' => $request,
            ]);
						}
					}	
				}
			}
        } else {
            return $this->render('create', ['request' => $request, 
            ]);
        }
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
