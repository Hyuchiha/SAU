<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Areas */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="areas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('app', 'id'),
                'value' => $model->id,
            ],
            [
                'label' => Yii::t('app', 'area_id'),
                'value' => $model->area_id,
            ],
            [
                'label' => Yii::t('app', 'id_responsible'),
                'value' => $model->idResponsable->first_name,
            ],
            [
                'label' => Yii::t('app', 'name'),
                'value' => $model->name,
            ],
            [
                'label' => Yii::t('app', 'description'),
                'value' => $model->description,
            ],
        ],
    ]) ?>

</div>
