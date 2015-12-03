<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Areas;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Areas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="areas-form">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'area_id')->dropDownList(
         ArrayHelper::map(
             Areas::find()->all(),
             'id',
             'name'
         ), array('prompt' => "")
     )->label(Yii::t('app', 'area_id')) ?>

    <?= $form->field($model, 'id_responsable')->dropDownList(ArrayHelper::map(User::find()->all(),'id','user_name'))
    ->label(Yii::t('app', 'id_responsible')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])
    ->label(Yii::t('app', 'name')) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true])
    ->label(Yii::t('app', 'description')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
