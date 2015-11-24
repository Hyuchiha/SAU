<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = $request->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $request->subject, 'url' => ['view', 'id' => $request->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Advanced Options');
?>
<div class="request-update">

    <h1><?= Html::encode("Request: " .$this->title) ?></h1>

    <?= $this->render('formadvancedoptions', [
        'request' => $request,
    ]) ?>

</div>