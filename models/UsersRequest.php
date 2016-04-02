<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_request".
 *
 * @property string $request_id
 * @property string $user_id
 *
 * @property Request $request
 * @property User $user
 */
class UsersRequest extends \yii\db\ActiveRecord
{
    public $cnt;
    public $month;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'user_id'], 'required'],
            [['request_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => Yii::t('app', 'Request ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
