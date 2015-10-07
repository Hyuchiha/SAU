<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "areas_request".
 *
 * @property string $solicitude_id
 * @property string $area_id
 * @property string $completion_date
 * @property string $acceptance_date
 * @property string $assignment_date
 *
 * @property Areas $area
 * @property Request $solicitude
 */
class AreasRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'areas_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['solicitude_id', 'area_id'], 'required'],
            [['solicitude_id', 'area_id'], 'integer'],
            [['completion_date', 'acceptance_date', 'assignment_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'solicitude_id' => 'Solicitude ID',
            'area_id' => 'Area ID',
            'completion_date' => 'Completion Date',
            'acceptance_date' => 'Acceptance Date',
            'assignment_date' => 'Assignment Date',
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
    public function getSolicitude()
    {
        return $this->hasOne(Request::className(), ['id' => 'solicitude_id']);
    }
}
