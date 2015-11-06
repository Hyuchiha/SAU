<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Categories;
use app\models\Areas;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

<?php 
        $cat = Categories::find()->all();
        $cat2 = Categories::find();
        $numRecords = $cat2->count();
       
         //Crear espacios 
        for ($i=0; $i < $numRecords ; $i++) { 

                $parrent = $cat[$i]['category_id'];
                $n = 0;
                while ($parrent != NULL) {
                    $parrentTemp = $cat2->where(['id'=>$parrent])->all();
                    $parrent = $parrentTemp[0]['category_id'];
                    $n = $n + 1;
                }
                $cat[$i]['name'] = str_repeat("--", $n)."\n".$cat[$i]['name'];
        }

      //Ordenar nodos
        $order = array();
    for ($i=0; $i < $numRecords ; $i++) { 
        $parrent = $cat[$i]['category_id'];
        if($parrent == NULL){
            //es categoria
            array_push($order,$cat[$i]);
         }

    }
    for ($i=0; $i < $numRecords ; $i++) { 
        $parrent = $cat[$i]['category_id'];
         if ($parrent != NULL) {
            //es subcategoria
            $order2 = array();
            foreach ($order as $val) {
                if($val['id'] == $parrent){
                 array_push($order2,$val);
                 array_push($order2,$cat[$i]);

                }else{
                 array_push($order2,$val);  
                }
                $order = $order2;
            }
         }         
    }   
    ?>
    <?php $form = ActiveForm::begin();  ?>

    <?= $form->field($model, 'category_id')->dropDownList(
    ArrayHelper::map($order2,'id','name'), array('prompt'=>''))?>

    <?= $form->field($model, 'id_area')->dropDownList(ArrayHelper::map(Areas::find()->all(),'id','name')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'service_level_agreement_asignment')->textInput() ?>

    <?= $form->field($model, 'service_level_agreement_completion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
