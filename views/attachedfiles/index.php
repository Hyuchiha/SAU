<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AttachedFilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attached Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attached-files-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Attached Files', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'request_id',
            'url:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
