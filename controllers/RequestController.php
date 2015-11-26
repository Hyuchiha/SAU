<?php

namespace app\controllers;

use Yii;
use app\models\Request;
use app\models\AreasRequest;
use app\models\CategoryRequest;
use app\models\UsersRequest;
use app\models\RequestSearch;
use app\models\AttachedFiles;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;
use yii\filters\VerbFilter;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['advanced-options'],
                        'roles' => ['administrator', 'responsibleArea','executive','employeeArea'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view','create'],
                        'roles' => ['@','?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['responsibleArea', 'administrator', 'employeeArea','executive'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['responsibleArea', 'administrator'],
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
	
		public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        $request = new Request(['scenario'=>'Create']);
		$areasRequest = new AreasRequest();
		$categoryRequest = new CategoryRequest();
		//$usersRequest = new UsersRequest();

        if ($request->load(Yii::$app->request->post())) {
			$request->requestFile = UploadedFile::getInstances($request, 'requestFile');
			
			$valid = true;
			//$valid = $valid && $request->validate();
			
			
			if($valid){
				if($request->save()){
					
					if(Yii::$app->user->isGuest){
						$cookieName = new Cookie([
						'name' => 'name',
						'value' => $request->name,
						]);
						\Yii::$app->getResponse()->getCookies()->add($cookieName);
						$cookieEmail = new Cookie([
						'name' => 'email',
						'value' => $request->email,
						]);
						\Yii::$app->getResponse()->getCookies()->add($cookieEmail);
					}
				
					if($valid && !empty($request->requestFile)){
						$request->upload();
					}
					$areasRequest->request_id = $request->id;
					$areasRequest->area_id = $request->area_id;
					
					$categoryRequest->request_id = $request->id;
					$categoryRequest->category_id = $request->category_id;

					if(!empty($request->category_id)){
						$categoryRequest->save();
					}
					
					if($areasRequest->save()){

						if($valid){
							return $this->redirect(['view', 'id' => $request->id]);
						
						}else{
							return $this->render('create', ['request' => $request,]);
						}
					}else {
						return $this->render('create', ['request' => $request,]);
					}	
				}else {
					return $this->render('create', ['request' => $request, ]);
				}
			}else {
				return $this->render('create', ['request' => $request,]);
			}
        } else {
            return $this->render('create', ['request' => $request,]);
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
        $request = $this->findModel($id);

        if ($request->load(Yii::$app->request->post()) && $request->save()) {
            return $this->redirect(['view', 'id' => $request->id]);
        } else {
            return $this->render('update', [
                'request' => $request,
            ]);
        }
    }
	
	    public function actionAdvancedOptions($id)
    {
        $request = $this->findModel($id);
		
        if ($request->load(Yii::$app->request->post()) && $request->save()) {
			if(!empty($request->listAreas)){
				if($request->assignAreas()){
				
				}else {
					return $this->render('advanced-options', ['request' => $request,]);
				}	
			}
			if(!empty($request->listCategories)){
				if($request->assignCategories()){

				}else {
					return $this->render('advanced-options', ['request' => $request,]);
				}	
			}
			return $this->redirect(['view', 'id' => $request->id]);
        } else {
            return $this->render('advanced-options', [
                'request' => $request,
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
    public function actionChat() {

        //if (!empty($_POST)) {

            echo \sintret\chat\ChatRoom::sendChat($_POST);
        //}
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
