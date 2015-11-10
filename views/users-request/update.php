<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UsersRequest */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Users Request',
]) . ' ' . $model->request_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_id, 'url' => ['view', 'request_id' => $model->request_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="users-request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
