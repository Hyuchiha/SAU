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
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['administrator', 'executive','responsibleArea'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['administrator', 'executive','responsibleArea'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['administrator', 'executive','responsibleArea'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['administrator', 'executive','responsibleArea'],
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

                foreach($areaPersonal->getUsersToAssing() as $user){
                    $areaPersonalNew = new AreaPersonal();

                    $areaPersonalNew->area_id = $areaPersonal->area_id;
                    $areaPersonalNew->user_id = $user;
                    $areaPersonalNew->permission = $areaPersonal->permission;

                    $alreadyAssigned = $this->checkSave($user);

                    if(!$alreadyAssigned && $user != $user1){
                        $areaPersonalNew->save();
                    }
                }

                $alreadyAssigned = $this->checkSave($user1);


                if($alreadyAssigned){
                    return $this->redirect(['index']);
                }

                $areaPersonal->setUser($user1);

                if($areaPersonal->save()){
                    //return $this->redirect(['view', 'area_id' => $areaPersonal->area_id, 'user_id' => $areaPersonal->user_id]);
                    return $this->redirect(['index']);
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
     * @param $user
     * @return bool
     */
    private function checkSave($user){
        $listAssigned = (new \yii\db\Query())
            ->select(['user_id'])
            ->from('area_personal')
            ->where(['user_id' => $user])
            ->all();

        $alreadyAssigned = false;

        foreach($listAssigned as $assigned){
            if($user === $assigned["user_id"]){
                $alreadyAssigned = true;
                break;
            }
        }
        return $alreadyAssigned;
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
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRole('employeeArea');

        $model = $this->findModel($area_id, $user_id);

        $authManager->revoke($role, $model->user_id);

        $model->delete();

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
