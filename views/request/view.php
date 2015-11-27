<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = Yii::t('app', $model->subject);
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);//$this->title;
?>
<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>
       
        

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>


		<?= Html::a(Yii::t('app', 'Advanced options'), ['advanced-options', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
	
<?php	
	
	if(empty ($model->scheduled_start_date)){
		$start_date = "--";
	}else{
		$start_date = $model->scheduled_start_date;
	}
	
	if(empty ($model->scheduled_end_date)){
		$end_date = "--";
	}else{
		$end_date = $model->scheduled_end_date;
	}
	
	if(empty ($model->completion_date)){
		$comp_date = "--";
	}else{
		$comp_date = $model->completion_date;
	}

	$area_name="";
	if(!empty ($model->areas)){
		foreach($model->areas as $area){
			$area_name = $area_name . $area->name . ", ";
		}
	}
	
	$category_name="";
	if(!empty ($model->categories)){
		foreach($model->categories as $category){
			$category_name = $category_name . $category->name . ", ";
		}
	}

?>

    <?= DetailView::widget([
 'model' => $model,
        'attributes' => [
            //'id',
            //'area_id',
			[
				'label' => Yii::t('app', 'Area'),
				'value' => $model->area->name,
			],
			[
				'label' => Yii::t('app', 'name'),
				'value' => $model->name,
			],
			[
				'label' => Yii::t('app', 'email'),
				'value' => $model->email,
			],
			[
				'label' => Yii::t('app', 'subject'),
				'value' => $model->subject,
			],
			[
				'label' => Yii::t('app', 'Assigned areas'),
				'value' => $area_name,
			],
			[
				'label' => Yii::t('app', 'Assigned categories'),
				'value' => $category_name,
			],
			[
				'label' => Yii::t('app', 'Scheduled start date'),
				'value' => $start_date,
			],
			[
				'label' => Yii::t('app', 'Scheduled end date'),
				'value' => $end_date,
			],
			'creation_date',
			[
				'label' => Yii::t('app', 'Completion date'),
				'value' => $comp_date,
			],
			[
				'label' => Yii::t('app', 'status'),
				'value' => $model->status,
			],
        ],
    ]) ?>
<?php if(!empty ($model->attachedFiles)):	?>
	
	<h2>Attached files</h2>
	<?php foreach ($model->attachedFiles as $attachedFile): ?>
	<?= DetailView::widget([
		'model' => $attachedFile,
		'attributes' => [
			[
				'label' => Yii::t('app', 'File'),
				'value' => Html::a($attachedFile->url,'@web/files/'.$attachedFile->url),
				'format' => 'html',
			],
		],
    ]) ?>

	
	<?php endforeach; ?>
	<?php endif; ?>

    <?php
    echo \sintret\chat\ChatRoom::widget([
            'url' => \yii\helpers\Url::toRoute(['/request/chat']),
            //'requestModel'=> \app\models\Request::className(),
            'userModel'=>  \app\models\User::className(),
            'userField'=>'avatarImage',
            'idRequest' => $model->id,
            'userName' => $model->name

        ]
    );
    ?>
	
	
</div>