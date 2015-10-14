<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AttachedFiles */

$this->title = 'Create Attached Files';
$this->params['breadcrumbs'][] = ['label' => 'Attached Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attached-files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
