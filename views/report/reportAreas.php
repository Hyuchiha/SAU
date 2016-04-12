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

$this->title = Yii::t('app', 'Reportes Áreas');
$this->params['breadcrumbs'][] = Yii::t('app', 'Requests');


?>
<div class="request-index">

    <div id="buttons">


        <?= Html::a ( 'Áreas', $url = 'areas', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Categorias', $url = 'categorias', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Recibidos', $url = 'recibidos', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Atendidos', $url = 'atendidos', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Reportes Encuestas', $url = 'index', $options = ['class'=>'btn btn-primary'] ) ?>


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

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [

                'attribute'=>Yii::t('app','Nombre de Área Padre'),
                'value'=>'idArea'
            ],
            [

                'attribute'=>Yii::t('app','Nombre de Área/Sub-Área'),
                'value'=>'areaname'
            ],
            [

                'attribute'=>Yii::t('app','Num de Reportes'),
                'value'=>'cnt'
            ],



        ],
    ]); ?>
    <?php Pjax::end(); ?>








    
