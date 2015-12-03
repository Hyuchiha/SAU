<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Categories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'category_id',
                'value' => 'category_id',
                'label' => Yii::t('app', 'category_id')
            ],
            [
                'attribute' => 'id_area',
                'value' => 'idArea.name',
                'label' => Yii::t('app', 'area')
            ],
            [
                'attribute' => 'name',
                'value' => 'name',
                'label' => Yii::t('app', 'name')
            ],
            [
                'attribute' => 'description',
                'value' => 'description',
                'label' => Yii::t('app', 'description')
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
