<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\AreaPersonal;
use app\models\AreaPersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AreaPersonalController implements the CRUD actions for AreaPersonal model.
 */
class AreaPersonalController extends Controller
{
    public function behaviors()
    {
        return [
            /*'access' => [
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
            ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AreaPersonal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AreaPersonalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AreaPersonal model.
     * @param string $area_id
     * @param string $user_id
     * @return mixed
     */
    public function actionView($area_id, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($area_id, $user_id),
        ]);
    }

    /**
     * Creates a new AreaPersonal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $areaPersonal = new AreaPersonal();

        if ($areaPersonal->load(Yii::$app->request->post())) {

            if(!empty($areaPersonal->usersToAssing)){

                $user1 = $areaPersonal->getFirtsElmentOfUsers();
                $areaPersonal->setUser($user1);

                foreach($areaPersonal->getUsersToAssing() as $user){
                    $areaPersonalNew = new AreaPersonal();

                    $areaPersonalNew->area_id = $areaPersonal->area_id;
                    $areaPersonalNew->user_id = $user;
                    $areaPersonalNew->permission = $areaPersonal->permission;

                    $areaPersonalNew->save();
                }

                if($areaPersonal->save()){
                    return $this->redirect(['view', 'area_id' => $areaPersonal->area_id, 'user_id' => $areaPersonal->user_id]);
                }
            }else{
                return $this->render('create', [
                    'model' => $areaPersonal,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $areaPersonal,
            ]);
        }
    }

    /**
     * Updates an existing AreaPersonal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $area_id
     * @param string $user_id
     * @return mixed
     */
    public function actionUpdate($area_id, $user_id)
    {
        $model = $this->findModel($area_id, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'area_id' => $model->area_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AreaPersonal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $area_id
     * @param string $user_id
     * @return mixed
     */
    public function actionDelete($area_id, $user_id)
    {
        $this->findModel($area_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AreaPersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $area_id
     * @param string $user_id
     * @return AreaPersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($area_id, $user_id)
    {
        if (($model = AreaPersonal::findOne(['area_id' => $area_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
