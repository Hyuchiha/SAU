<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UsersRequest */

$this->title = Yii::t('app', 'Create Users Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
