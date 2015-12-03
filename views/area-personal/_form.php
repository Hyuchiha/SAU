<?php

use app\models\User;
use unclead\widgets\MultipleInput;
use unclead\widgets\MultipleInputColumn;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
        ), array('prompt' => ""))->label(Yii::t('app', 'area')) ?>

    <?php if($model->isNewRecord){ ?>

    <?= $form->field($model, 'usersToAssing')->widget(MultipleInput::className(), [
        'limit' => 100,
        'allowEmptyList' => true,
        'columns'=> [
          [
              'name' => 'usersToAssing',
              'type' => MultipleInputColumn::TYPE_DROPDOWN,
              'items'=>ArrayHelper::map(
                  User::find()->all(),
                  'id',
                  'first_name',
                  'lastname'
              ),
          ]
        ]])->label(Yii::t('app', 'User to assing')) ?>

    <?php }else{ ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(
            User::find()->all(),
            'id',
            'first_name'
        )
    )->label(Yii::t('app', 'User to assing')) ?>

    <?php } ?>


    <?= $form->field($model, 'permission')->radioList(
        [
            1 => 'Si',
            0 => 'No',
        ]
    )->label(Yii::t('app', 'permission')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
