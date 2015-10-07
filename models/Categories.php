<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $id_area
 * @property string $name
 * @property string $description
 * @property string $service_level_agreement
 *
 * @property Areas $idArea
 * @property CategorySolicitude[] $categorySolicitudes
 * @property Solicitudes[] $solicitudes
 * @property Solicitudes[] $solicitudes0
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'id_area'], 'integer'],
            [['id_area', 'name', 'description'], 'required'],
            [['service_level_agreement'], 'safe'],
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
            'category_id' => Yii::t('app', 'Category ID'),
            'id_area' => Yii::t('app', 'Id Area'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'service_level_agreement' => Yii::t('app', 'Service Level Agreement'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdArea()
    {
        return $this->hasOne(Areas::className(), ['id' => 'id_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategorySolicitudes()
    {
        return $this->hasMany(CategorySolicitude::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudes()
    {
        return $this->hasMany(Solicitudes::className(), ['id' => 'solicitude_id'])->viaTable('category_solicitude', ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudes0()
    {
        return $this->hasMany(Solicitudes::className(), ['service_id' => 'id']);
    }
}
