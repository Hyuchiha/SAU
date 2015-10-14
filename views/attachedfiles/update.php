<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AttachedFiles */

$this->title = 'Update Attached Files: ' . ' ' . $model->request_id;
$this->params['breadcrumbs'][] = ['label' => 'Attached Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_id, 'url' => ['view', 'request_id' => $model->request_id, 'url' => $model->url]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attached-files-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
