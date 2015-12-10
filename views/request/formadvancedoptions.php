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
			'Nuevo'=>Yii::t('app', 'New'),				//Nuevo
			'Asignado'=>Yii::t('app', 'Assigned'),		//Asignado
			'En proceso'=>Yii::t('app', 'In process'),	//En proceso
			'Verificado'=>Yii::t('app', 'Checked'),		//Verificado
			'Rechazado'=>Yii::t('app', 'Denaied'),		//Rechazado
			'Finalizado'=>Yii::t('app', 'Completed'),	//Finalizado
		]
	)->label(Yii::t('app', 'status')) ?>
	
	<?= $form->field($request, 'scheduled_start_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	])->label(Yii::t('app', 'Scheduled start date')) ?>
	
	<?= $form->field($request, 'scheduled_end_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	])->label(Yii::t('app', 'Scheduled end date')) ?>

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
	
	<?= $form->field($request, 'listRemoveAreas')->checkboxList($areasRequests)->label(Yii::t('app', 'Remove areas')) ?>
	
	<?php endif ?>
	
	<?php
		$categoriesRequests =ArrayHelper::map(Categories::find()
		->innerJoin('category_request', 'category_request.category_id = categories.id')
		->where(['request_id' => $request->id])
		->all(),'id','name');
	?>
	
	<?php if(!empty($categoriesRequests)) :?>
	
	<?= $form->field($request, 'listRemoveCategories')->checkboxList($categoriesRequests)->label(Yii::t('app', 'Remove categories')) ?>
		
	<?php endif ?>
	
	<?php
		$usersRequests =ArrayHelper::map(User::find()
		->innerJoin('users_request', 'users_request.user_id = users.id')
		->where(['request_id' => $request->id])
		->all(),'id','first_name');
	?>
	
	<?php if(!empty($usersRequests)) :?>
	
	<?= $form->field($request, 'listRemoveUsers')->checkboxList($usersRequests)->label(Yii::t('app', 'Remove personal')) ?>
		
	<?php endif ?>
	
    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>