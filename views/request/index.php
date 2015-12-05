<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Request;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Requests');
$this->params['breadcrumbs'][] = Yii::t('app', 'Requests');
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Request', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'value' => 'id',
                'label' => Yii::t('app', 'id'),
            ],
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
			[
                'attribute' => 'creation_date',
                'value' => 'creation_date',
                'label' => Yii::t('app', 'creation_date'),
            ],
			[
                'attribute' => 'status',
                'value' => 'status',
				'filter' => Html::activeDropDownList(
					$searchModel, 'status', 
						ArrayHelper::map(Request::find()->asArray()->all(),'status', 'status'),
					['class'=>'form-control','prompt' => Yii::t('app', 'Select one status')]
				),
                'label' => Yii::t('app', 'status'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
