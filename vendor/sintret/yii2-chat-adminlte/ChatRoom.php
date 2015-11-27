<?php
/**
 * @link https://github.com/sintret/yii2-chat-adminlte
 * @copyright Copyright (c) 2015 Andy fitria <sintret@gmail.com>
 * @license MIT
 */
namespace sintret\chat;
use Yii;
use yii\base\Widget;
use sintret\chat\models\Chat;
use app\models\User;
/**
 * @author Andy Fitria <sintret@gmail.com>
 */
class ChatRoom extends Widget {
    public $sourcePath = '@vendor/sintret/yii2-chat-adminlte/assets';
    public $css = [
    ];
    public $js = [ // Configured conditionally (source/minified) during init()
        'js/chat.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $models;
    public $url;
    public $userModel;
    public $userField;
    public $idRequest;
    public $userName;
    public $model;
    public $loadingImage;
    public function init() {
        $this->model = new Chat();
        if ($this->userModel === NULL) {
            $this->userModel = Yii::$app->getUser()->identityClass;
        }
        $this->model->userModel = $this->userModel;
        if ($this->userField === NULL) {
            $this->userField = 'avatarImage';
        }

        if ($this->idRequest === NULL) {
            $this->idRequest = 'idrequestnulo';
        }
        if($this->userName === NULL){
            $this->userName = 'NULL';
        }

        $this->model->userField = $this->userField;
        $this->model->idRequest = $this->idRequest;
        $this->model->userName = $this->userName;
        Yii::$app->assetManager->publish("@vendor/sintret/yii2-chat-adminlte/assets/img/loadingAnimation.gif");
        $this->loadingImage = Yii::$app->assetManager->getPublishedUrl("@vendor/sintret/yii2-chat-adminlte/assets/img/loadingAnimation.gif");
        parent::init();
    }
    public function run() {
        parent::init();
        ChatJs::register($this->view);
        $model = new Chat();
        $model->userModel = $this->userModel;
        $model->userField = $this->userField;
        $model->idRequest = $this->idRequest;
        $model->userName = $this->userName;
        $data = $model->data($this->idRequest);
        return $this->render('index', [
                    'data' => $data,
                    'url' => $this->url,
                    'userModel' => $this->userModel,
                    'userField' => $this->userField,
                    'idRequest'=>$this->idRequest,
                    'userName' => $this->userName,
                    'loading' => $this->loadingImage
        ]);
    }
    public static function sendChat($post) {
        if (isset($post['message']))
            $message = $post['message'];
        if (isset($post['userfield']))
            $userField = $post['userfield'];
        if (isset($post['idRequest']))
            $idRequest = $post['idRequest'];
        if (isset($post['userName']))
            $userName = $post['userName'];
        if (isset($post['model']))
            $userModel = $post['model'];
        else
            $userModel = Yii::$app->getUser()->identityClass;
        $model = new \sintret\chat\models\Chat;
        $model->userModel = $userModel;
        if ($userField)
            $model->userField = $userField;
        if ($message) {
            $model->message = $message;
            $model->request_id = $idRequest;
            if(!Yii::$app->user->isGuest){
                $model->userId = Yii::$app->user->id;
                $user = User::findIdUserName(Yii::$app->user->id);
                $model->user_name = $user->user_name;


            }else{
                $model->userId = Yii::$app->user->id;
                $model->user_name = $userName;
            }
            
            
            
            
            if ($model->save()) {
                echo $model->data($idRequest);
            } else {
                print_r($model->getErrors());
                exit(0);
            }
        } else {
            echo $model->data($idRequest);
        }
    }
}