<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
			$cookies = Yii::$app->request->cookies;
			if (isset($cookies['name'])){
				$nameValue = $cookies['name']->value;
				$emailValue = $cookies['email']->value;
			}else{
				$nameValue = "";
				$emailValue = "";
			}
		}else{
			$user = User::findOne(\Yii::$app->user->identity->id);
			$nameValue = $user->first_name . " " . $user->lastname;
			$emailValue = $user->email;
		}
	?>
	
	<?= $form->field($request, 'name')->textInput([
		'value'=>$nameValue,'maxlength' => true,
		'placeholder' => Yii::t('app', 'Please enter your name'),
	]) ?>
	
	<?= $form->field($request, 'email')->textInput([
		'value'=> $emailValue,'maxlength' => true,
		'placeholder' => Yii::t('app', 'Please enter your email'),
	]) ?>
	
    <?= $form->field($request, 'area_id')->dropDownList(
		ArrayHelper::map(
			Areas::find()->all(),
			'id',
			'name'
		),
		['prompt'=> Yii::t('app', 'Please select an area'),
		'onchange'=>'$.get( "'.Url::toRoute('/categories/lists').'", { id: $(this).val() } )
            .done(function( data ) {
					$( "#'.Html::getInputId($request, 'category_id').'" ).html( data );
				}
            );'
		]) ?>
		
	<?= $form->field($request, 'category_id')->dropDownList(
		ArrayHelper::map(
			Categories::find()->all(),
			'id',
			'name'
		), array('prompt'=> Yii::t('app', 'Optional: Please select a category'),)) ?>
		

    <?= $form->field($request, 'subject')->textInput([
		'maxlength' => true,
		'placeholder' => Yii::t('app', 'Please enter your subject'),
	]) ?>

    <?= $form->field($request, 'description')->textarea([
		'rows' => 6,
		'placeholder' => Yii::t('app', 'Please enter the request´s description'),
	]) ?>
	

    <a id="agregarCampo" class="btn btn-info" >Agregar Archivo</a>
    <div id="contenedor">
        <div class="added">
            <!--<input type="file" name="Request[requestFile][]" id="campo_1" placeholder="Texto 1"/><a href="#" class="eliminar">&times;</a>-->
        </div>
    </div>

    <br>
	
	<?= $form->field($request, 'verifyCode')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-lg-1.5">{image}</div><div class="col-lg-2">{input}</div></div>',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($request->isNewRecord ? 'Create' : 'Update', ['class' => $request->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
