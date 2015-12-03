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

    <?php $form = ActiveForm::begin();  ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(
            Categories::find()->all(),
            'id',
            'name'
        ), array('prompt'=>''))?>

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
