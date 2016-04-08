<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Request;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reportes Recibidos');
$this->params['breadcrumbs'][] = Yii::t('app', 'Requests');
?>
<div class="request-index">

    <div id="buttons">


        <?= Html::a ( 'Áreas', $url = 'areas', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Categorias', $url = 'categorias', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Recibidos', $url = 'recibidos', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Atendidos', $url = 'atendidos', $options = ['class'=>'btn btn-primary'] ) ?>


        <br>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="reports-form">



        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'dateInit')->widget(\yii\jui\DatePicker::classname(), [
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>

        <?= $form->field($model, 'dateFinish')->widget(\yii\jui\DatePicker::classname(), [
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>

        <p class="form-group">
            <?= Html::submitButton('Consultar', ['class' => 'btn btn-primary', 'id'=>'consultarButton']) ?>
        </p>

        <?php ActiveForm::end(); ?>


    </div>

    <div>
        <table class="table">
            <thead>
            <tr>
                <th>Mes</th>
                <?php foreach ($datos as $dat)
                    echo '<th>'. $dat['areaname'] .'   </th>';

                ?>

            </tr>
            </thead>
            <tbody>


            <?php
            //echo json_encode($dataProvider->allModels[1]["month"]);
            //echo '<tr><td>'. $dataProvider->allModels[0]["month"].  '</td></tr>';
            ?>
            <?php

            $aux = null;
            foreach ($dataProvider->allModels as $data){
                echo '<tr>';
                echo '<td>'. $data['month'] .'</td>';

                foreach ($datos as $dato){
                    $bandera = false;
                    foreach ($datos2 as $dato2){

                        if($data['month']==$dato2['month'] && $dato['areaname'] == $dato2['areaname']){

                            $bandera = true;
                            $aux = $dato2['cnt'];
                        }

                    }
                    if($bandera){
                        echo '<td>'.$aux.'</td>';
                    }else{
                        echo '<td>0</td>';
                    }
                }


                echo '</tr>';
            }
            ?>



            </tbody>
        </table>



    </div>
