<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $lastname
 * @property string $hash_password
 * @property string $user_name
 * @property string $email
 * @property string $status
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'lastname', 'hash_password', 'user_name', 'email'], 'required'],
            [['hash_password'], 'required', 'except' => ['update']],
            [['first_name', 'lastname', 'username'], 'string', 'max' => 128],
            [['hash_password'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'Nombre'),
            'lastname' => Yii::t('app', 'Apellido'),
            'hash_password' => Yii::t('app', 'Contraseña'),
            'user_name' => Yii::t('app', 'Usuario'),
            'email' => Yii::t('app', 'Correo electrónico'),
            'status' => Yii::t('app', 'Estado'),
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
                $this->access_token = Yii::$app->getSecurity()->generateRandomString();
            }
            else
            {
                if(!empty($this->password))
                {
                    $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                }
            }
            return true;
        }
        return false;
    }

    public static function findIdentity($id)
    {
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($user_name)
    {
        return self::findOne(['user_name'=>$username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        return Yii::$app->getSecurity()->validatePassword($password,$this->hash_password);
    }
}
