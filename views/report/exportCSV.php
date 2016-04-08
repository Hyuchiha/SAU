
<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReportForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Export CSV');
$this->params['breadcrumbs'][] =$this->title;
?>

<?php $this->registerJs('
    $(\'#report-form\').on(\'submit\', function(e) {
        var dateInit = $(\'#reportform-startdate\').val();
        var dateFinish = $(\'#reportform-enddate\').val();

        if (!/Invalid|NaN/.test(new Date(dateInit))) {
            if(new Date(dateFinish) > new Date(dateInit)){
                return true;
            }else{
                alert("Porfavor ingrese bien la fecha");
                return false;
            }
        }else{
            alert(\'Fecha no válida\');
            return false;
        }
    });
'); ?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="reports-export-form container">
    <h2 class="page-header"><small><i class="glyphicon glyphicon-open"></i> <?php echo Yii::t('app','Export CSV')?></small></h2>
    <p><?php echo Yii::t('app','Select the start and end dates of the reports your want export to CSV')?></p>
    <br>

    <?php $form = ActiveForm::begin([
        'id' => 'report-form',
        'options' => ['class' => 'form-horizontal', 'role' => 'form'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]) ?>

    <?= $form->field($model, 'startDate')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ])->label(Yii::t('app','Start Date')) ?>

    <?= $form->field($model, 'endDate')->widget(\yii\jui\DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
    ])->label(Yii::t('app','End Date')) ?>

    <div>
        <?= Html::submitButton(Yii::t('app','Export'), ['class' => 'btn btn-primary btn-margin']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <!--<?= Html::a(Yii::t('app','Export CSV'), ['export'], ['class' => 'btn btn-success']) ?> -->
</div>
