<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area_personal".
 *
 * @property string $area_id
 * @property string $user_id
 * @property integer $permission
 *
 * @property Areas $area
 * @property Users $user
 */
class AreaPersonal extends \yii\db\ActiveRecord
{
    public $usersToAssing;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'area_personal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'permission'], 'required'],
            [['area_id', 'user_id', 'permission'], 'integer'],
            [['usersToAssing'], 'checkIsArray'],
        ];
    }


    public function checkIsArray(){
        if(!is_array($this->usersToAssing)){

        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_id' => Yii::t('app', 'Area ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'permission' => Yii::t('app', 'Permission'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Areas::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
