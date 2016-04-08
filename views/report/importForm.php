<?php
use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\importForm*/

$this->title = Yii::t('app', 'Import CSV');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<div class="reports-import-form container">
    <h2 class="page-header"><small><i class="glyphicon glyphicon-save"></i> <?php echo Yii::t('app','Import CSV')?></small></h2>
    <p><?php echo Yii::t('app','Select any CSV file that you wish to import to the system')?></p>
    <br>
    <br>
    <div id="form-import">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]) ?>

        <?= $form->field($model, 'csv')->fileInput()->label(Yii::t('app','CSV')) ?>

        <div>
            <?= Html::submitButton(Yii::t('app','Import'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
