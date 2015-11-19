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
                'label' => Yii::t('app', 'area_id')
            ],
			Yii::t('app', 'name'), 
			Yii::t('app', 'email:email'), 
            Yii::t('app', 'subject'),
            Yii::t('app', 'description:ntext'),
            // 'creation_date',
            // 'completion_date',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
