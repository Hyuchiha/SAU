<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Request',
]) . ' ' . $request->id;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $request->id, 'url' => ['view', 'id' => $request->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'request' => $request,
    ]) ?>

</div>
