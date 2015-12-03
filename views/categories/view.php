<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-view">

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
                'label' => Yii::t('app', 'category_id'),
                'value' => $model->category_id,
            ],
            [
                'label' => Yii::t('app', 'id_area'),
                'value' => $model->idArea->name,
            ],
            [
                'label' => Yii::t('app', 'name'),
                'value' => $model->name,
            ],
            [
                'label' => Yii::t('app', 'description'),
                'value' => $model->description,
            ],
            [
                'label' => Yii::t('app', 'service_level_agreement_asignment'),
                'value' => $model->service_level_agreement_asignment,
            ],
            [
                'label' => Yii::t('app', 'service_level_agreement_completion'),
                'value' => $model->service_level_agreement_completion,
            ],
        ],
    ]) ?>

</div>
