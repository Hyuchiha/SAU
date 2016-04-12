<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "areas".
 *
 * @property string $id
 * @property string $area_id
 * @property string $id_responsable
 * @property string $name
 * @property string $description
 *
 * @property AreaPersonal[] $areaPersonals
 * @property User[] $users
 * @property User $idResponsable
 * @property AreasRequest[] $areasRequests
 * @property Request[] $requests
 * @property Categories[] $categories
 * @property Request[] $requests0
 */
class Areas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'areas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'id_responsable'], 'integer'],
            [['name', 'description'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_id' => 'Area ID',
            'id_responsable' => 'Id Responsable',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)) {

            $auth = Yii::$app->authManager;
            $jefeArea = $auth->getRole('responsibleArea');

            $roles = $auth->getRolesByUser($this->id_responsable);

            $isResponsibleOfArea = false;
            foreach($roles as $role){
                if($role == $jefeArea){
                    $isResponsibleOfArea = true;
                }
            }

            if(!$isResponsibleOfArea){
                $auth->assign($jefeArea, $this->id_responsable);
                return true;
            }else{
                return true;
            }

        }

        return false;
    }

    public function beforeDelete(){
        if(parent::beforeDelete()) {

            $auth = Yii::$app->authManager;
            $jefeArea = $auth->getRole('responsibleArea');
            $auth->revoke($jefeArea, $this->id_responsable);

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreaPersonals()
    {
        return $this->hasMany(AreaPersonal::className(), ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('area_personal', ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdResponsable()
    {
        return $this->hasOne(User::className(), ['id' => 'id_responsable']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreasRequests()
    {
        return $this->hasMany(AreasRequest::className(), ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['id' => 'request_id'])->viaTable('areas_request', ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id_area' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Request::className(), ['service_id' => 'id']);
    }
}
