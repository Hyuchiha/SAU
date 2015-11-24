<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requests');
$this->params['breadcrumbs'][] = Yii::t('app', 'Requests');
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            Yii::t('app', 'id'),
            [
                'attribute' => 'area_name',
                'value' => 'area.name',
                'label' => Yii::t('app', 'area'),
            ],
            [
                'attribute' => 'name',
                'value' => 'name',
                'label' => Yii::t('app', 'name'),
            ],
            [
                'attribute' => 'email',
                'value' => 'email',
                'label' => Yii::t('app', 'email'),
            ],
            [
                'attribute' => 'subject',
                'value' => 'subject',
                'label' => Yii::t('app', 'subject'),
            ],
            [
                'attribute' => 'description',
                'value' => 'description',
                'label' => Yii::t('app', 'description'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
