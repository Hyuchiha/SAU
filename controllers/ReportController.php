<?php
/**
 * Created by PhpStorm.
 * User: Kev'
 * Date: 05/03/2016
 * Time: 12:22 PM
 */

namespace app\controllers;

use app\models\AreasRequest;
use app\models\Areas;
use app\models\CategoryRequest;
use app\models\Categories;
use app\models\UsersRequest;

use app\models\importForm;
use app\models\ReportForm;
use app\models\Request;
use app\models\UsersRequestSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use DateTime;

class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','export', 'recibidos','areas','atendidos','categorias','user','polls','control-report',
                            'import'],
                        'roles' => ['administrator', 'responsibleArea','executive','?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
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
        return $this->render('index');
    }

    public function actionExport()
    {
        $model = new ReportForm();

        if ($model->load(Yii::$app->request->post()) && $model) {
            $init = $model->startDate;
            $finish = $model->endDate;

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');

            // create a file pointer connected to the output stream
            $output = fopen('php://output', 'w');

            // output the column headings
            fputcsv($output, array('tid', 'firstname', 'lastname', 'email', 'emailstatus', 'token', 'language', 'validfrom', 'validuntil',
                'invited', 'reminded', 'remindercount', 'completed', 'usesleft', 'attribute_1 <ID_Reporte>', 'attribute_2 <Categoría>',
                'attribute_3 <Área>', 'attribute_4 <Título>', 'attribute_5 <dtSolicitud>'));

            // fetch the data
            $rows = Request::find()->andFilterWhere(['between', 'creation_date', $init, $finish])->all();

            // loop over the rows, outputting them
            foreach ($rows as $row) {
                $request = $this->findModel($row->getAttribute('id'));

                $data = array(
                    $row->getAttribute('id'),
                    $row->getAttribute('name'),
                    '\'\'',
                    $row->getAttribute('email'),
                    'OK',
                    '\'\'',
                    'es',
                    '\'\'',
                    '\'\'',
                    'N',
                    'N',
                    $row->getAttribute('status'),
                    'N',
                    '1',
                    $row->getAttribute('id'),
                    $request->getStringOfCategories(),
                    $request->area->name,
                    $row->getAttribute('subject'),
                    $row->getAttribute('description'));

                fputcsv($output, $data);
            }

            fclose($output);
        } else {
            return $this->render('exportCSV', ['model' => $model]);
            //return JSON::encode($html);
        }
    }

    public function actionAttended()
    {

    }

    public function actionCategories()
    {
    }

    public function actionUser()
    {

    }
    public function actionPolls()
    {
        if(Yii::$app->request->isAjax){
            $model = new ReportForm();

            $html = $this->renderAjax('reportsByPollForm', [
                'model' => $model,
            ]);

            return JSON::encode($html);
        }else{
            $model = new ReportForm();
            $request = Yii::$app->request;
            $model->load($request->post());

            $dataProvider = new ArrayDataProvider([
                'allModels' => Request::find()->Where(['between', 'request.completion_date', $model->startDate, $model->endDate])->all(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('reportsByPoll', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionImport(){
        if(Yii::$app->request->isGet){
            $model = new importForm();

            return $this->render('importForm', [
                'model' => $model,
            ]);
        }else{
            $model = new importForm();

            $model->csv = UploadedFile::getInstance($model, 'csv');

            if($model->upload()){
                $i=0; $keys=array();$output=array();
                $handle=fopen("uploads/".$model->csv, "r");
                if ($handle){
                    while(($line = fgetcsv($handle)) !== false) {
                        $i++;
                        if ($i==1) {
                            $keys=$line;
                        }
                        elseif ($i>1){
                            $attr=array();
                            foreach($line as $k=>$v){
                                $attr[$keys[$k]]=$v;
                            }
                            $output[]=$attr;
                        }
                    }
                    fclose($handle);
                }

                $this->saveData($output);
                return $this->render('index');
            }
            return $this->render('index');
        }
    }


    private function saveData($data){
        foreach($data as $line){
            $request = $this->findModel($line['id']);
            $request->satisfaccion = $line['satisfaccion'];
            $request->level = $line['level'];
            $request->save();
        }
    }

    public function actionControlReport(){
        return $this->render('controls');
    }

    public function actionAreas(){        

        $model = new ReportForm();
        if($model->load(Yii::$app->request->post()) && $model) {
            $consulta = AreasRequest::find()
                    ->select(['areas.name AS areaname', 'COUNT(`areas_request`.`area_id`) AS cnt',
                    'areas.area_id AS idArea'])
                    ->leftJoin('areas','areas_request.area_id = areas.id')
                    ->leftJoin('request','areas_request.request_id = request.id')
                    ->Where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('areas_request.area_id')
                    ->all();

            foreach ($consulta as $key => $value) {
                if(empty($value->idArea)){
                    $value->idArea = 'Área Padre';                                    
                    
                }else{
                    $nameA = Areas::find()
                    ->select('areas.name')
                    ->where(['areas.id'=>$value->idArea])
                    ->one();
                    $value->idArea = $nameA->name;                                                            
                }
            }


            $dataProvider = new ArrayDataProvider([
                'allModels' => $consulta,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportAreas',['dataProvider'=>$dataProvider,'model'=>$model]);
        }else{
            $consulta1 = AreasRequest::find()
                    ->select(['areas.name AS areaname', 'COUNT(`areas_request`.`area_id`) AS cnt',
                        'areas.area_id AS idArea'])
                    ->leftJoin('areas','areas_request.area_id = areas.id')
                    ->leftJoin('request','areas_request.request_id = request.id')
                    ->groupBy('areas_request.area_id')
                    ->all();
                   
            foreach ($consulta1 as $key => $value) {
                if(empty($value->idArea)){
                    $value->idArea = 'Área Padre';                                    
                    
                }else{
                    $nameA = Areas::find()
                    ->select('areas.name')
                    ->where(['areas.id'=>$value->idArea])
                    ->one();
                    $value->idArea = $nameA->name;                                                            
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $consulta1,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            
            return $this->render('reportAreas',['dataProvider'=>$dataProvider,'model'=>$model]);
        }


    }

    public function actionCategorias(){
        $model = new ReportForm();
        if($model->load(Yii::$app->request->post()) && $model) {

            $consulta = CategoryRequest::find()
                    ->select(['categories.name AS categoryname', 'COUNT(`category_request`.`category_id`) AS cnt',
                        'categories.category_id AS idCategory'])
                    ->leftJoin('categories','category_request.category_id = categories.id')
                    ->leftJoin('request','category_request.request_id = request.id')
                    ->Where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('category_request.category_id')
                    ->all();

                foreach ($consulta as $key => $value) {
                if(empty($value->idCategory)){
                    $value->idCategory = 'Categoria Padre';                                    
                    
                }else{
                    $nameC = Categories::find()
                    ->select('categories.name')
                    ->where(['categories.id'=>$value->idCategory])
                    ->one();
                    $value->idCategory = $nameC->name;                                                            
                }
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $consulta,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportCategoria',['dataProvider'=>$dataProvider,'model'=>$model]);
        }else{

            $consulta1 = CategoryRequest::find()
                    ->select(['categories.name AS categoryname',
                        'COUNT(`category_request`.`category_id`) AS cnt','categories.category_id AS idCategory'])
                    ->leftJoin('categories','category_request.category_id = categories.id')
                    ->leftJoin('request','category_request.request_id = request.id')                    
                    ->groupBy('category_request.category_id')
                    ->all();

            foreach ($consulta1 as $key => $value) {
                if(empty($value->idCategory)){
                    $value->idCategory = 'Categoria Padre';                                    
                    
                }else{
                    $nameC = Categories::find()
                    ->select('categories.name')
                    ->where(['categories.id'=>$value->idCategory])
                    ->one();
                    $value->idCategory = $nameC->name;                                                            
                }
            }
                                    
            $dataProvider = new ArrayDataProvider([
                'allModels' => $consulta1,                    
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);


            return $this->render('reportCategoria',['dataProvider'=>$dataProvider,'model'=>$model]);
            
        }
    }

    public function actionRecibidos(){
        $model = new ReportForm();
        if($model->load(Yii::$app->request->post()) && $model) {

            $datos = Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                ->groupBy('request.area_id')
                ->all();
            $datos2 = Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                ->groupBy('MONTHNAME(request.creation_date)')
                ->addGroupBy('request.area_id')
                ->all();
            $dataProvider = new ArrayDataProvider([
                'allModels' => Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                    'areas.name AS areaname'])
                    ->innerJoin('areas','request.area_id = areas.id')
                    ->where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('MONTHNAME(request.creation_date)')
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportRecibidos',['dataProvider'=>$dataProvider,'datos'=>$datos,'datos2' => $datos2,'model'=>$model]);
        }else{

            $date = date("Y-m-d");

            $datos = Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'creation_date', '2016-01-01', $date])
                ->groupBy('request.area_id')
                ->all();
            $datos2 = Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.creation_date', '2016-01-01', $date])
                ->groupBy('MONTHNAME(request.creation_date)')
                ->addGroupBy('request.area_id')
                ->all();
            $dataProvider = new ArrayDataProvider([
                'allModels' => Request::find()->select(['MONTHNAME(request.creation_date) AS month','COUNT(*) AS cnt',
                    'areas.name AS areaname'])
                    ->innerJoin('areas','request.area_id = areas.id')
                    ->where(['between', 'request.creation_date', '2016-01-01', $date])
                    ->groupBy('MONTHNAME(request.creation_date)')
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->render('reportRecibidos',['dataProvider'=>$dataProvider,'datos'=>$datos,'datos2' => $datos2,'model'=>$model]);
        }
    }

    public function actionAtendidos(){
        $model = new ReportForm();
        if($model->load(Yii::$app->request->post()) && $model) {

            $datos = Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.completion_date', $model->dateInit, $model->dateFinish])
                ->groupBy('request.area_id')
                ->all();
            $datos2 = Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.completion_date', $model->dateInit, $model->dateFinish])
                ->groupBy('MONTHNAME(request.completion_date)')
                ->addGroupBy('request.area_id')
                ->all();
            $dataProvider = new ArrayDataProvider([
                'allModels' => Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                    'areas.name AS areaname'])
                    ->innerJoin('areas','request.area_id = areas.id')
                    ->where(['between', 'request.completion_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('MONTHNAME(request.completion_date)')                    
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportAtendidos',['dataProvider'=>$dataProvider,'datos'=>$datos,'datos2' => $datos2,'model'=>$model]);
        }else{

            $date = date("Y-m-d");

            $datos = Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.completion_date', '2016-01-01', $date])
                ->groupBy('request.area_id')
                ->all();
            $datos2 = Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                'areas.name AS areaname'])
                ->leftJoin('areas','request.area_id = areas.id')
                ->where(['between', 'request.completion_date', '2016-01-01', $date])
                ->groupBy('MONTHNAME(request.completion_date)')
                ->addGroupBy('request.area_id')
                ->all();
            $dataProvider = new ArrayDataProvider([
                'allModels' => Request::find()->select(['MONTHNAME(request.completion_date) AS month','COUNT(*) AS cnt',
                    'areas.name AS areaname'])
                    ->innerJoin('areas','request.area_id = areas.id')
                    ->where(['between', 'request.completion_date', '2016-01-01', $date])
                    ->groupBy('MONTHNAME(request.completion_date)')
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->render('reportAtendidos',['dataProvider'=>$dataProvider,'datos'=>$datos,'datos2' => $datos2,'model'=>$model]);
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