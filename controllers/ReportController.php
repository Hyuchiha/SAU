<?php

namespace app\controllers;

use app\models\Areas;
use app\models\Categories;
use app\models\ReportForm;
use app\models\Request;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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