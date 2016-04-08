<?php
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reports by poll');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="reports-attended-index">
    <div id="gridView">
        <?= GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'pjax'=>true,
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'width' => '30px',
                ],
                [

                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>Yii::t('app','Request'),
                    'value'=>'subject'
                ],
                [

                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>Yii::t('app','Satisfaccion'),
                    'value'=>'satisfaccion'
                ],
                [

                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>Yii::t('app','Level'),
                    'value'=>'level'
                ],
            ],
            'toolbar'=> [

            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'default',
                'heading' => '<h4><i class="glyphicon glyphicon-list"></i> ' .
                    Yii::t('app','Reports by polls'). '</h4>',
            ]
        ]) ?>
    </div>
</div>
