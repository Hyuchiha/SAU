<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Areas;
use app\models\Categories;
use app\models\User;
use app\models\AttachedFiles;
use yii\widgets\ActiveField;
use yii\captcha\Captcha;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $request app\models\Request */
/* @var $form yii\widgets\ActiveForm */
 /*   <?= $form->field($request, 'completion_date')->widget(\yii\jui\DatePicker::classname(), [
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>*/
?>

<?php
$this->registerJs('

    var MaxInputs       = 8; //Número Maximo de Campos
    var contenedor       = $("#contenedor"); //ID del contenedor
    var AddButton       = $("#agregarCampo"); //ID del Botón

    //var x = número de campos existentes en el contenedor
    var x = $("#contenedor div").length + 1;
    var FieldCount = x-1; //para el seguimiento de los campos

    $(AddButton).click(function (e) {
        if(x <= MaxInputs) //max input box allowed
        {
            FieldCount++;
            //agregar campo
            $(contenedor).append(\'<div><a class="eliminar">&times;</a><input type="file" name="Request[requestFile][]"></div>\');
            x++; //text box increment
        }
        });

     $("body").on("click",".eliminar", function(e){ //click en eliminar campo
        if( x > 1 ) {
            $(this).parent("div").remove(); //eliminar el campo
            x--;
        }
        return false;
    });


');

?>

<div class="request-form">
	
    <?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data']
	]) ?>
	
		<?php
		if(Yii::$app->user->isGuest){
			$nameValue = "";
			$emailValue = "";
		}else{
			$user = User::findOne(\Yii::$app->user->identity->id);
			$nameValue = $user->first_name . " " . $user->lastname;
			$emailValue = $user->email;
		}
	?>
	
	<?= $form->field($request, 'name')->textInput(['value'=>$nameValue,'maxlength' => true]) ?>
	
	<?= $form->field($request, 'email')->textInput(['value'=> $emailValue,'maxlength' => true]) ?>
	
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
		

    <?= $form->field($request, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($request, 'description')->textarea(['rows' => 6]) ?>
	

    <a id="agregarCampo" class="btn btn-info" >Agregar Archivo</a>
    <div id="contenedor">
        <div class="added">
            <!--<input type="file" name="Request[requestFile][]" id="campo_1" placeholder="Texto 1"/><a href="#" class="eliminar">&times;</a>-->
        </div>
    </div>

    <br>
	


    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
