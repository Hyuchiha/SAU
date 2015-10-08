<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Areas;
//use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */

/*<?= $form->field($model, 'creation_date')->textInput() ?>*/

//dropDownList
/*<?= $form->field($model, 'service_id')->textInput(['maxlength' => true]) ?>]*/
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'service_id')->dropDownList(
		ArrayHelper::map(
			Areas::find()->all(),
			'id',
			'name'
		)
	) ?>
	
    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

	<?= $form->field($model, 'creation_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>

    <?= $form->field($model, 'completion_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
