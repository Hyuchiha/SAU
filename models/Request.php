<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property string $id
 * @property string $user_id
 * @property string $service_id
 * @property string $subject
 * @property string $description
 * @property string $creation_date
 * @property string $completion_date
 * @property string $status
 *
 * @property AreasRequest[] $areasRequests
 * @property Areas[] $areas
 * @property AttachedFiles[] $attachedFiles
 * @property CategoryRequest[] $categoryRequests
 * @property Categories[] $categories
 * @property Categories $service
 * @property Users $user
 * @property UsersRequest[] $usersRequests
 * @property Users[] $users
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'service_id', 'subject', 'description', 'creation_date', 'completion_date', 'status'], 'required'],
            [['user_id', 'service_id'], 'integer'],
            [['creation_date', 'completion_date'], 'safe'],
            [['subject'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 150],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'service_id' => 'Service ID',
            'subject' => 'Subject',
            'description' => 'Description',
            'creation_date' => 'Creation Date',
            'completion_date' => 'Completion Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreasRequests()
    {
        return $this->hasMany(AreasRequest::className(), ['solicitude_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areas::className(), ['id' => 'area_id'])->viaTable('areas_request', ['solicitude_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachedFiles()
    {
        return $this->hasMany(AttachedFiles::className(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryRequests()
    {
        return $this->hasMany(CategoryRequest::className(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])->viaTable('category_request', ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Categories::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersRequests()
    {
        return $this->hasMany(UsersRequest::className(), ['solicitude_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable('users_request', ['solicitude_id' => 'id']);
    }
}
