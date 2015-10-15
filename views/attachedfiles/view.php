<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AttachedFiles */

$this->title = $model->request_id;
$this->params['breadcrumbs'][] = ['label' => 'Attached Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attached-files-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'request_id' => $model->request_id, 'url' => $model->url], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'request_id' => $model->request_id, 'url' => $model->url], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'request_id',
            'url:url',
        ],
    ]) ?>

</div>
