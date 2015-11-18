<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AreaPersonal */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Area Personal',
]) . ' ' . $model->area_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Area Personals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->area_id, 'url' => ['view', 'area_id' => $model->area_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="area-personal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>