<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            //'id',
            //'area_id',
			[
				'label' => 'Area',
				'value' => $model->area->name,
			],
			'name', 
			'email:email',
            'subject',
            'description:ntext',
            'creation_date',
            'completion_date',
            'status',
        ],
    ]) ?>
	
	<?php if(!empty ($model->attachedFiles)):	?>
	
	<h2>Attached files</h2>
	<?php foreach ($model->attachedFiles as $attachedFile): ?>
	<?= DetailView::widget([
		'model' => $attachedFile,
		'attributes' => [
			[
				'label' => 'url',
				'value' => Html::a($attachedFile->url,'@web/files/'.$attachedFile->url),
				'format' => 'html',
			],
		],
    ]) ?>
	<?php endforeach; ?>
	<?php endif; ?>
	
	<?php 

		$model->status = "Finalizado";
		
	?>
	
	<?php $form = ActiveForm::begin()?>
	<div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>
	<?php ActiveForm::end(); ?>
	
</div>
