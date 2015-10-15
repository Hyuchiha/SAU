<?php

namespace app\models;

use Yii;

use app\models\AttachedFiles;

/**
 * This is the model class for table "request".
 *
 * @property string $id
 * @property string $user_id
 * @property string $area_id
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
 * @property Areas $area
 * @property Users $user
 * @property UsersRequest[] $usersRequests
 * @property Users[] $users
 */
class Request extends \yii\db\ActiveRecord
{	
	public $requestFile;
	//public $requestFile;
	public $fileNameAttached;
	
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
            [['area_id', 'subject', 'description'], 'required'],
            [['user_id', 'area_id'], 'integer'],
            [['description'], 'string'],
            [['creation_date', 'completion_date'], 'safe'],
            [['subject'], 'string', 'max' => 500],
            [['status', 'fileNameAttached'], 'string', 'max' => 50],
			[['requestFile'], 'file', 'skipOnEmpty' => false, 'extensions'=>'pdf,png,jpg,jpeg,bmp,doc,docx', 'maxFiles' => 500],
			
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
            'area_id' => 'Area ID',
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
        return $this->hasMany(AreasRequest::className(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areas::className(), ['id' => 'area_id'])->viaTable('areas_request', ['request_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersRequests()
    {
        return $this->hasMany(UsersRequest::className(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id' => 'user_id'])->viaTable('users_request', ['request_id' => 'id']);
    }
	
	public function beforeSave($insert){
		if(parent::beforeSave($insert)){
			
			$formatedDateTime = date_format(date_create(),"Y/m/d H:i:s");
			$this->creation_date = $formatedDateTime;
			//$this->completion_date = $formatedDateTime;
			
			//$this->fileNameAttached = uniqid() . '.' . $this->requestFile->extension;
			//$this->requestFile->saveAs('files/'.$fileNameAttached);
			
			//$this->url = $fileNameAttached;
			
			if(empty($this->completion_date)){
				$this->completion_date = date_format(date_create("0000-00-00 00:00:00"),"Y/m/d H:i:s");
			}
			if(empty($this->status)){
				$this->status = "Nuevo";
			}
			
			return true;
		}
		return false;
	}
	
	public function upload(){
		if($this->validate()){
			foreach ($this->requestFile as $file){
				$this->fileNameAttached = uniqid() . '.' . $file->extension;
				$file->saveAs('files/'.$this->fileNameAttached);
				$attachedFiles = new AttachedFiles();
				$attachedFiles->request_id = $this->id;
				$attachedFiles->url = $this->fileNameAttached;
				$attachedFiles->save();
			}
			return true;
		} else{
			return false;
		}
	}
}
