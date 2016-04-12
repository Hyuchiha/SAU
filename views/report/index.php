<?php
use app\models\ReportForm;
use kartik\tabs\TabsX;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
    <div class="report-index">
        <div id="tab">
            <?= TabsX::widget([
                'items' => [
                    
                    [
                        'label'=>'<i class="glyphicon glyphicon-list"></i> ' . Yii::t('app','Reports based on polls'),
                        'linkOptions'=>['data-url'=>Url::to(['/report/polls'])],
                        
                        //'visible' => Yii::$app->user->can('read_requests_created'),
                        
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('app','Advanced options'),
                        'items' =>[
                            [
                                'label'=>Yii::t('app','Export CSV'),
                                'encode'=>false,
                                'content'=> Html::a(Yii::t('app','Export'), ['export'], ['class'=>'btn btn-primary pull-left']),
                            ],
                            [
                                'label'=>Yii::t('app','Import CSV'),
                                'encode'=>false,
                                'content'=> Html::a(Yii::t('app','Import'), ['import'], ['class'=>'btn btn-primary pull-left']),
                            ],
                        ],                        
                        'content'=>"", //$this->render('exportCSV', ['model' => new ReportForm()]),
                        'active'=>true,
                    ],
                ],
                'position' => TabsX::POS_ABOVE,
                'encodeLabels' => false,
                'pluginOptions' => [
                    'enableCache' => true,
                ]
            ])?>
        </div>
    </div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>