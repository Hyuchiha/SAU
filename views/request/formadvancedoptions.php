<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Areas;
use app\models\Categories;
use app\models\User;
use yii\widgets\ActiveField;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $request app\models\Request */
/* @var $form yii\widgets\ActiveForm */
 /*   <?= $form->field($request, 'completion_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>*/
?>

<div class="request-form">
	
    <?php $form = ActiveForm::begin() ?>

	<?= $form->field($request, 'status')->dropDownList(
		[
			'new'=>'New',					//Nuevo
			'assigned'=>'Assigned',			//Asignado
			'inProcess'=>'In process',		//En proceso
			'checked'=>'Checked',			//Verificado
			'denied'=>'Denaied',			//Rechazado
			'completed'=>'Completed',		//Finalizado
		]
	) ?>
	
	<?= $form->field($request, 'scheduled_start_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>
	
	<?= $form->field($request, 'scheduled_end_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>
	
	<?php
	$arrayAreas=ArrayHelper::map(Areas::find()->all(),'id','name');
	$areasRequests = (new \yii\db\Query())
    ->select(['area_id'])
    ->from('areas_request')
    ->where(['request_id' => $request->id])
    ->all();
	if(!empty($areasRequests)){
		foreach ($areasRequests as $areaRequest){
			unset($arrayAreas[$areaRequest["area_id"]]);
		}
	}
	?>
	
	<?= $form->field($request, 'listAreas')->checkboxList($arrayAreas) ?>
	
	<?php
	$arrayCategories=ArrayHelper::map(Categories::find()->all(),'id','name');
	$categoriesRequests = (new \yii\db\Query())
    ->select(['category_id'])
    ->from('category_request')
    ->where(['request_id' => $request->id])
    ->all();
	if(!empty($categoriesRequests)){
		foreach ($categoriesRequests as $categoryRequest){
			unset($arrayCategories[$categoryRequest["category_id"]]);
		}
	}
	?>
	
	<?= $form->field($request, 'listCategories')->checkboxList($arrayCategories) ?>
	
    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>