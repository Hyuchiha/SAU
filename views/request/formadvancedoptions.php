<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Areas;
use app\models\Categories;
use app\models\User;
use yii\widgets\ActiveField;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
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

	<?= $form->field($request, 'listAreas')->widget(MultipleInput::className(), [
        'limit' => 100,
        'allowEmptyList' => true,
        'columns'=> [
          [
              'name' => 'listAreas',
              'type' => MultipleInputColumn::TYPE_DROPDOWN,
              'items'=>ArrayHelper::map(
                  Areas::find()->orderBy('name')->all(),
                  'id',
                  'name'
              ), 
          ]
        ]])->label(Yii::t('app', 'Assign areas')) 
	?>
	
	<?= $form->field($request, 'listCategories')->widget(MultipleInput::className(), [
        'limit' => 100,
        'allowEmptyList' => true,
        'columns'=> [
          [
              'name' => 'listCategories',
              'type' => MultipleInputColumn::TYPE_DROPDOWN,
              'items'=>ArrayHelper::map(
                  Categories::find()->orderBy('name')->all(),
                  'id',
                  'name'
              ),
          ]
        ]])->label(Yii::t('app', 'Assign categories')) 
	?>
	
	<?= $form->field($request, 'listPersonel')->widget(MultipleInput::className(), [
        'limit' => 100,
        'allowEmptyList' => true,
        'columns'=> [
          [
              'name' => 'listPersonel',
              'type' => MultipleInputColumn::TYPE_DROPDOWN,
              'items'=>ArrayHelper::map(
                  User::find()->orderBy('lastname')->all(),
                  'id',
                  'first_name',
				  'lastname'
              ),
          ]
        ]])->label(Yii::t('app', 'Assign personal'))
	?>
	
	<?php
		$areasRequests =ArrayHelper::map(Areas::find()
		->innerJoin('areas_request', 'areas_request.area_id = areas.id')
		->where(['request_id' => $request->id])
		->all(),'id','name');
	?>
	
	<?php if(!empty($areasRequests)) :?>
	
	<?= $form->field($request, 'listRemoveAreas')->checkboxList($areasRequests) ?>
	
	<?php endif ?>
	
	<?php
		$categoriesRequests =ArrayHelper::map(Categories::find()
		->innerJoin('category_request', 'category_request.category_id = categories.id')
		->where(['request_id' => $request->id])
		->all(),'id','name');
	?>
	
	<?php if(!empty($categoriesRequests)) :?>
	
	<?= $form->field($request, 'listRemoveCategories')->checkboxList($categoriesRequests) ?>
		
	<?php endif ?>
	
    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>