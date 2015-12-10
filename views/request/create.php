<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $request app\models\Request */

$this->title = Yii::t('app','Create Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create Request');
?>
<div class="request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('requestFormSubmitted')): ?>

        <div class="alert alert-success">
            Su solicitud fue registrada y le hemos enviado a su correo un enlace para que pueda darle seguimiento a su solicitud.
        </div>
    <?php endif; ?> 

    <?= $this->render('_form', [
        'request'  => $request,
    ]) ?>

</div>
