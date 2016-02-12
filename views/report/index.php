<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReportForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = Yii::t('app', 'Reports');
?>

<?php $this->registerJs('
    $(\'#w0\').on(\'submit\', function(e) {
        var dateInit = $(\'#reportform-dateinit\').val();
        var dateFinish = $(\'#reportform-datefinish\').val();

        if (!/Invalid|NaN/.test(new Date(dateInit))) {
            if(new Date(dateFinish) > new Date(dateInit)){
                return true;
            }else{
                alert("Porfavor ingrese una bien la fecha");
                return false;
            }
        }else{
            alert(\'Date not valid\');
            return false;
        }
    });
'); ?>


<div class="reports-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'dateInit')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'dateFinish')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <p class="form-group">
        <?= Html::submitButton('Export', ['class' => 'btn btn-success']) ?>
    </p>

    <?php ActiveForm::end(); ?>

    <!--<?= Html::a(Yii::t('app','Export CSV'), ['export'], ['class' => 'btn btn-success']) ?> -->
</div>
