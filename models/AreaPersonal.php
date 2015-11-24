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
 * @property User $user
 */
class AreaPersonal extends \yii\db\ActiveRecord
{
    public $usersToAssing = array();

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
            [['usersToAssing'], 'validateArray'],
        ];
    }

    public function validateArray($attribute)
    {
        $items = $attribute;
        if (!is_array($items)) {
            $items = [];
        }
        foreach ($items as $index => $item) {
            $validator = $this->findOne(['user_id', $item]);
            $error = null;
            if (!empty($validator)) {
                $validator->validate($item['usersToAssing'], $error);
                if (!empty($error)) {
                    $key = $attribute . '[' . $index . ']';
                    $this->addError($key, $error);
                }
            }
        }
    }

    /**
     *
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            //Aqui se agregan los permisos al usuario
            $auth = Yii::$app->authManager;
            $employeeArea = $auth->getRole('employeeArea');
            $auth->assign($employeeArea, $this->user_id);

            return true;
        }
        return false;
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

    public function getFirtsElmentOfUsers()
    {
        $user = array_pop($this->usersToAssing);

        unset($this->usersToAssing[$user]);

        return $user;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user_id = $user;
    }

    /**
     * @return array
     */
    public function getUsersToAssing()
    {
        return $this->usersToAssing;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
