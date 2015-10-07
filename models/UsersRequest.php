<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_request".
 *
 * @property string $solicitude_id
 * @property string $user_id
 *
 * @property Request $solicitude
 * @property Users $user
 */
class UsersRequest extends \yii\db\ActiveRecord
{
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
            [['solicitude_id', 'user_id'], 'required'],
            [['solicitude_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'solicitude_id' => 'Solicitude ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitude()
    {
        return $this->hasOne(Request::className(), ['id' => 'solicitude_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
