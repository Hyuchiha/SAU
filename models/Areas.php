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
 * @property Users[] $users
 * @property Users $idResponsable
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
            'id' => Yii::t('app', 'ID'),
            'area_id' => Yii::t('app', 'Area ID'),
            'id_responsable' => Yii::t('app', 'Id Responsable'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
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
        return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable('area_personal', ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdResponsable()
    {
        return $this->hasOne(Users::className(), ['id' => 'id_responsable']);
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
