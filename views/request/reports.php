<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Request;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reportes');
$this->params['breadcrumbs'][] = Yii::t('app', 'Requests');
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,        
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            [
                'attribute' => 'area_name',
                'value' => 'area.name',
                'label' => 'Total',
            ],
            [
    'attribute' => 'Total de reportes',
    'format' => 'raw',
    'value' => function ($datos) {                      
            return '<div>'.$datos->totales.' and other html-code</div>';
    },
],
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>




<?php
    $columns =  array('class'=>'yii\grid\SerialColumn','nombre1' => $arrayDataProvider->allModels['area1']['nombre1'],'count1'=>$arrayDataProvider->allModels['area1']['count1'],'nombre2' => $arrayDataProvider->allModels['area2']['nombre2'],'count2'=>$arrayDataProvider->allModels['area2']['count2']);
    $column2 = array('nombre2' => $arrayDataProvider->allModels['area2']['nombre2'],'count2'=>$arrayDataProvider->allModels['area2']['count2']);
    



?>
    <?= GridView::widget([
        'dataProvider' => $arrayDataProvider,            
        'filterModel' => $searchModel,
        'columns' =>[
            ['class' => 'yii\grid\SerialColumn'],            
            [
                'attribute' => 'area_name',
                'value' => 'area.name',
                'label' => 'Total',
            ],
            
            

            ['class' => 'yii\grid\ActionColumn'],
        ], 
    ]); ?>


    <div>
        <p>Totales: 
        <?php   
            //var_dump(sizeof($datos));

            //for($i=1;$i<=sizeof($datos);$i++){

              //  echo $datos['area'.$i]['nombre'.$i]."<br>";

               var_dump($arrayDataProvider->allModels['area9']['nombre9']);
            //}

        ?>

        
    <div>

</div>
