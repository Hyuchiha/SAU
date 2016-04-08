<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Request;
use app\models\AreasRequest;
use app\models\AreaPersonal;
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
                        'roles' => ['administrator', 'responsibleArea', 'executive', 'employeeArea'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'create'],
                        'roles' => ['@', '?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['chat'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['token'],
                        'roles' => ['?', '@'],
                    ],



                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['responsibleArea', 'administrator', 'employeeArea', 'executive'],
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
        if(Yii::$app->user->can('administrator') || Yii::$app->user->can('executive')){
            //Ver todas las solicitudes
            $searchModel = new RequestSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }else{
            $queryParams = array_merge(array(),Yii::$app->request->getQueryParams());
            $permission = NULL;
            if(!Yii::$app->user->can('responsibleArea'))
                $permission = AreaPersonal::findOne(['user_id'=>Yii::$app->user->getId()])->permission;
            if(Yii::$app->user->can('responsibleArea') || $permission == 1){
                //Ver solo las solicitudes del área correspondiente, las asignadas al usuario y las creadas por este
                $areaID = AreaPersonal::findOne(['user_id'=>Yii::$app->user->getId()])->area_id;
                $queryParams["RequestSearch"]["area_id"] = $areaID;
                $searchModel = new RequestSearch();
                $dataProvider = $searchModel->search($queryParams);
            }else{
                $queryParams["RequestSearch"]["user_id"] = Yii::$app->user->id;
                $searchModel = new RequestSearch();
                $dataProvider = $searchModel->search($queryParams);
            }
        }
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
    public function actionView($id, $token='')
    {

        $model = $this->findModel($id);
        if(Yii::$app->user->isGuest && !empty($model->token) && $token == $model->token )
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            if(!Yii::$app->user->isGuest){
                return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
            }else{
                throw new NotFoundHttpException('The requested page does not exist.');
            }

        }

    }


    public function actionToken($token){
        $request = Request::findOne(['token'=>$token]);


        return $this->redirect(['view', 'id' => $request->id]);

    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = new Request(['scenario' => 'Create']);
        $areasRequest = new AreasRequest();
        $categoryRequest = new CategoryRequest();
        //$usersRequest = new UsersRequest();

        if ($request->load(Yii::$app->request->post()) && $request) {
            $request->requestFile = UploadedFile::getInstances($request, 'requestFile');

            $valid = true;
            //$valid = $valid && $request->validate();


            if ($valid) {
                if ($request->save()) {
                    if(Yii::$app->user->isGuest){
                            $tokenEmail = urlencode($request->token);
                            $idEmail = urlencode($request->id);
                            $subject = "Token Solicitud";
                            $body = "<h1>Haga click en el siguiente enlace para poder dar seguimiento </h1>";
                            $body .= $tokenEmail;
                            $body .= "<a href='http://localhost/SAU/web/request/view?id=".$idEmail."&token=".$tokenEmail."'>Ver Solicitud</a>";

                            //Enviamos el correo
                            Yii::$app->mailer->compose()
                             ->setTo($request->email)
                             ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["title"]])
                             ->setSubject($subject)
                             ->setHtmlBody($body)
                             ->send();
                        }
                    if ($valid && !empty($request->requestFile)) {
                        $request->upload();

                        //localhost/SAU/web/request/view?id=15&&token=GUiSpF_XXVKnyNuof3-15bAMN7T8oBVj

                    }
                    $areasRequest->request_id = $request->id;
                    $areasRequest->area_id = $request->area_id;

                    $categoryRequest->request_id = $request->id;
                    $categoryRequest->category_id = $request->category_id;

                    if (!empty($request->category_id) || $request->category_id != 0) {
                        $categoryRequest->save();
                    }

                    if ($areasRequest->save()) {

                        if ($valid) {
                            Yii::$app->session->setFlash('requestFormSubmitted');
                            if(Yii::$app->user->isGuest){
                                return $this->redirect(['view', 'id' => $request->id, 'token' => $request->token]);
                            }else{
                                return $this->redirect(['view', 'id' => $request->id]);
                            }



                        } else {
                            return $this->render('create', ['request' => $request,]);
                        }
                    } else {
                        return $this->render('create', ['request' => $request,]);
                    }
                } else {
                    return $this->render('create', ['request' => $request,]);
                }
            } else {
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
            if (!empty($request->listAreas)) {
                if ($request->assignAreas()) {

                } else {
                    return $this->render('advanced-options', ['request' => $request,]);
                }
            }
            if (!empty($request->listCategories)) {
                if ($request->assignCategories()) {

                } else {
                    return $this->render('advanced-options', ['request' => $request,]);
                }
            }
            if (!empty($request->listPersonel)) {
                if ($request->assignPersonel()) {

                } else {
                    return $this->render('advanced-options', ['request' => $request,]);
                }
            }

            if (!empty($request->listRemoveCategories)) {
                if ($request->removeCategories()) {

                } else {
                    return $this->render('advanced-options', ['request' => $request,]);
                }
            }

            if (!empty($request->listRemoveAreas)) {
                if ($request->removeAreas()) {

                } else {
                    return $this->render('advanced-options', ['request' => $request,]);
                }
            }

			if (!empty($request->listRemoveUsers)) {
                if ($request->removeUsers()) {

                } else {
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

    public function actionChat()
    {

        if (!empty($_POST)) {

        echo \sintret\chat\ChatRoom::sendChat($_POST);
        }
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
