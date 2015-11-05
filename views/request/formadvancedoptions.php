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
	
	<?= $form->field($request, 'listAreas')->checkboxList(ArrayHelper::map(
			Areas::find()->all(),
			'id',
			'name'
	)) ?>
	
	<?= $form->field($request, 'listCategories')->checkboxList(ArrayHelper::map(
			Categories::find()->all(),
			'id',
			'name'
	)) ?>
	
    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>