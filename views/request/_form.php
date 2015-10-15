<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Areas;
use app\models\Categories;
use app\models\User;
use app\models\AttachedFiles;
use yii\widgets\ActiveField;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $request app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">
	
    <?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]) ?>

    <?= $form->field($request, 'area_id')->dropDownList(
		ArrayHelper::map(
			Areas::find()->all(),
			'id',
			'name'
		), array('prompt'=> "")) ?>
		
	<?= $form->field($request, 'category_id')->dropDownList(
		ArrayHelper::map(
			Categories::find()->all(),
			'id',
			'name'
		), array('prompt'=> "")) ?>
		
	<?= $form->field($request, 'assigned_id')->dropDownList(
		ArrayHelper::map(
			User::find()->all(),
			'id',
			'first_name',
			'lastname'
		), array('prompt'=> "")) ?>

    <?= $form->field($request, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($request, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($request, 'completion_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>
	
	<?= $form->field($request, 'requestFile[]')->fileInput(['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
