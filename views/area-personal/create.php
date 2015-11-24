<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AreaPersonal */

$this->title = Yii::t('app', 'Create Area Personal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Area Personals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-personal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
