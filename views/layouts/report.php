<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => 'SAU',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                /*[
                    'label' => Yii::t('app', 'Home'),
                    'url' => ['/site/index']
                ],*/
                //['label' => 'Contact', 'url' => ['/site/contact']],
                !Yii::$app->user->isGuest && Yii::$app->user->can('executive') ?
                    [
                        'label' => Yii::t('app', 'Request'),
                        'url' => ['/request/index']
                    ]:
                    [
                        'label' => Yii::t('app', 'Request'),
                        'url' => ['/request/create']
                    ],
                [
                    'label' => Yii::t('app', 'Requests'),
                    'url' => ['/request/index'],
                    'visible' => Yii::$app->user->can('responsibleArea')
                ],
                [
                    'label' => Yii::t('app', 'Users'),
                    'url' => ['/user/index'],
                    'visible' => Yii::$app->user->can('administrator')
                ],
                [
                    'label' => Yii::t('app', 'Categories'),
                    'url' => ['/categories/index'],
                    'visible' => Yii::$app->user->can('executive') || Yii::$app->user->can('administrator') ||
                        Yii::$app->user->can('responsibleArea')
                ],
                [
                    'label' => Yii::t('app', 'Areas'),
                    'url' => ['/areas/index'],
                    'visible' => Yii::$app->user->can('executive') || Yii::$app->user->can('administrator') ||
                        Yii::$app->user->can('responsibleArea')
                ],
                [
                    'label' => Yii::t('app', 'Assign Personal'),
                    'url' => ['/area-personal/index'],
                    'visible' => Yii::$app->user->can('executive') || Yii::$app->user->can('administrator') ||
                        Yii::$app->user->can('responsibleArea')
                ],
                Yii::$app->user->isGuest ?
                    ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']] :
                    [
                        'label' => Yii::t('app', 'Logout').' (' . Yii::$app->user->identity->user_name . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>