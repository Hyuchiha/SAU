<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Areas;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AreaPersonal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-personal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'area_id')->dropDownList(
        ArrayHelper::map(
            Areas::find()->all(),
            'id',
            'name'
        ), array('prompt'=> "")) ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(
            User::find()->all(),
            'id',
            'first_name'
        ), array('prompt'=> "")
    ) ?>

    <?= $form->field($model, 'permission')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
