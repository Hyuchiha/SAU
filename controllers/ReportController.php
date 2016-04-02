<?php

namespace app\controllers;

use app\models\AreasRequest;
use app\models\CategoryRequest;
use app\models\UsersRequest;
use app\models\ReportForm;
use app\models\Request;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\data\ArrayDataProvider;

class ReportController extends Controller
{
    public function actionIndex()
    {
        $model = new ReportForm();

        if($model->load(Yii::$app->request->post()) && $model) {
            $init = $model->dateInit;
            $finish = $model->dateFinish;

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
                    '',
                    $row->getAttribute('email'),
                    'OK',
                    '',
                    'es',
                    '',
                    '',
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
        }else {
            return $this->render('index', ['model' => $model]);
        }
    }

    public function actionControlReport(){
        return $this->render('controls');
    }
    
    public function actionAreas(){

        $model = new ReportForm();
        if($model->load(Yii::$app->request->post()) && $model) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => AreasRequest::find()
                    ->select(['areas.name AS areaname', 'COUNT(`areas_request`.`area_id`) AS cnt',
                    'areas.area_id AS idArea'])
                    ->leftJoin('areas','areas_request.area_id = areas.id')
                    ->leftJoin('request','areas_request.request_id = request.id')
                    ->Where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('areas_request.area_id')
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportAreas',['dataProvider'=>$dataProvider,'model'=>$model]);
        }else{
            $dataProvider = new ArrayDataProvider([
                'allModels' => AreasRequest::find()
                    ->select(['areas.name AS areaname', 'COUNT(`areas_request`.`area_id`) AS cnt'])
                    ->leftJoin('areas','areas_request.area_id = areas.id')
                    ->leftJoin('request','areas_request.request_id = request.id')
                    ->groupBy('areas_request.area_id')
                    ->all(),
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
            $dataProvider = new ArrayDataProvider([
                'allModels' => CategoryRequest::find()
                    ->select(['categories.name AS categoryname', 'COUNT(`category_request`.`category_id`) AS cnt',
                        'categories.category_id AS idCategory'])
                    ->leftJoin('categories','category_request.category_id = categories.id')
                    ->leftJoin('request','category_request.request_id = request.id')
                    ->Where(['between', 'request.creation_date', $model->dateInit, $model->dateFinish])
                    ->groupBy('category_request.category_id')
                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('reportCategoria',['dataProvider'=>$dataProvider,'model'=>$model]);
        }else{
            $dataProvider = new ArrayDataProvider([
                'allModels' => CategoryRequest::find()
                    ->select(['categories.name AS categoryname',
                        'COUNT(`category_request`.`category_id`) AS cnt','categories.category_id AS idCategory'])
                    ->leftJoin('categories','category_request.category_id = categories.id')
                    ->leftJoin('request','category_request.request_id = request.id')
                    ->groupBy('category_request.category_id')
                    ->all(),
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