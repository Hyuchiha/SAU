<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


$this->params['breadcrumbs'][] = Yii::t('app', 'Reports');
?>



    <div id="buttons">
        

        <?= Html::a ( 'Áreas', $url = 'areas', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Categorias', $url = 'categorias', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Recibidos', $url = 'recibidos', $options = ['class'=>'btn btn-primary'] ) ?>
        <?= Html::a ( 'Atendidos', $url = 'atendidos', $options = ['class'=>'btn btn-primary'] ) ?>
        

        <br>
    </div>
    


