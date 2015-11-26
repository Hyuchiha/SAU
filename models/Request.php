<?php

namespace app\models;

use Yii;

use app\models\AttachedFiles;

/**
 * This is the model class for table "request".
 *
 * @property string $id
 * @property string $name 
 * @property string $email
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
 * @property UsersRequest[] $usersRequests
 * @property User[] $users
 * @property string $scheduled_start_date
 * @property string $scheduled_end_date
 */
class Request extends \yii\db\ActiveRecord
{	
	public $requestFile;
	public $fileNameAttached;
	public $category_id;
	public $verifyCode;
	public $listAreas;
	public $listCategories;
	public $listPersonel;
	public $listRemoveCategories;
	public $listRemoveAreas;
	
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
            [['email', 'name','area_id', 'subject', 'description'], 'required'],
            [['area_id', 'category_id'], 'integer'],
            [['description'], 'string'],
            [['creation_date', 'completion_date', 'scheduled_start_date', 'scheduled_end_date'], 'safe'],
            [['subject'], 'string', 'max' => 500],
            [['fileNameAttached', 'status'], 'string', 'max' => 50],
			[['name', 'email'], 'string', 'max' => 150],
			[['requestFile'], 'file', 'skipOnEmpty' => true, 'extensions'=>'pdf,png,jpg,jpeg,bmp,doc,docx', 'maxFiles' => 500],
			[['verifyCode'], 'captcha', 'on'=>'Create'],
			[['listAreas', 'listCategories', 'listPersonel','listRemoveCategories', 'listRemoveAreas'],'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            //'user_id' => 'User ID',
			'name' => Yii::t('app', 'Name'),
			'email' => Yii::t('app', 'Email'),
            'area_id' => Yii::t('app', 'Area'),
			'category_id' => Yii::t('app', 'Category'),
            'subject' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Description'),
            'creation_date' => Yii::t('app', 'Creation Date'),
            'completion_date' => Yii::t('app', 'Completion Date'),
			'verifyCode' => Yii::t('app', 'Verification Code'),
            'status' => Yii::t('app', 'Status'),
			'scheduled_start_date' => Yii::t('app', 'Scheduled Start Date'), 
			'scheduled_end_date' => Yii::t('app', 'Scheduled End Date'), 
			'listAreas' => Yii::t('app', 'Assign Areas'),
			'listCategories' => Yii::t('app', 'Assign Categories'),
			'listPersonel' => Yii::t('app', 'Assign Personel'),
			'listRemoveCategories' => Yii::t('app', 'Remove Categories'),
			'listRemoveAreas' => Yii::t('app', 'Remove Areas'),
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
			
			if($this->status == 'completed'){
				$formatedDateTime = date_format(date_create(),"Y/m/d H:i:s");
				$this->completion_date = $formatedDateTime;
			}else{
				$this->completion_date = null;
			}
			
			if($this->isNewRecord){
				$formatedDateTime = date_format(date_create(),"Y/m/d H:i:s");
				$this->creation_date = $formatedDateTime;
			}
			
			if(!empty($this->scheduled_start_date)){
				$formatedDateTime = date_format(date_create($this->scheduled_start_date ." 00:00:00"),"Y/m/d H:i:s");
				$this->scheduled_start_date = $formatedDateTime;
			}
			
			if(!empty($this->scheduled_end_date)){
				$formatedDateTime = date_format(date_create($this->scheduled_end_date ." 00:00:00"),"Y/m/d H:i:s");
				$this->scheduled_end_date = $formatedDateTime;
			}

			//if(empty($this->completion_date)){
			//	$this->completion_date = date_format(date_create("0000-00-00 00:00:00"),"Y/m/d H:i:s");
			//}
			
			if(empty($this->status)){
				$this->status = "Nuevo";
			}
			
			return true;
		}
		return false;
	}
	
	public function upload(){
			foreach ($this->requestFile as $file){
				$this->fileNameAttached = uniqid() . '.' . $file->extension;
				$file->saveAs('files/'.$this->fileNameAttached);
				$attachedFiles = new AttachedFiles();
				$attachedFiles->request_id = $this->id;
				$attachedFiles->url = $this->fileNameAttached;
				$attachedFiles->save();
			}
	}
	
	public function assignAreas(){
	/*
		foreach ($this->listAreas as $area){
			//$this->fileNameAttached = uniqid() . '.' . $file->extension;
			//$file->saveAs('files/'.$this->fileNameAttached);
			$area_request = new AreasRequest();
			$area_request->request_id = $this->id;
			$area_request->area_id = $area;
			$area_request->save();
		}
		*/
		foreach ($this->listAreas as $area){
			$area_request = new AreasRequest();
			$area_request->request_id = $this->id;
			$area_request->area_id = $area;
			
			$listAssigned = (new \yii\db\Query())
			->select(['area_id'])
			->from('areas_request')
			->where(['request_id' => $this->id])
			->all();
			
			$alreadyAssigned = false;
			
			foreach($listAssigned as $assigned){
				if($area === $assigned["area_id"]){
					$alreadyAssigned = true;
					break;
				}
			}

			if(!$alreadyAssigned){
				$area_request->save();
			}

		}
		
		return true;
	}
	
	public function assignCategories(){
	/*
		foreach ($this->listCategories as $category){
			//$this->fileNameAttached = uniqid() . '.' . $file->extension;
			//$file->saveAs('files/'.$this->fileNameAttached);
			$category_request = new CategoryRequest();
			$category_request->request_id = $this->id;
			$category_request->category_id = $category;
			$category_request->save();
		}
		*/
		
		foreach ($this->listCategories as $category){
			$category_request = new CategoryRequest();
			$category_request->request_id = $this->id;
			$category_request->category_id = $category;
			
			$listAssigned = (new \yii\db\Query())
			->select(['category_id'])
			->from('category_request')
			->where(['request_id' => $this->id])
			->all();
			
			$alreadyAssigned = false;
			
			foreach($listAssigned as $assigned){
				if($category === $assigned["category_id"]){
					$alreadyAssigned = true;
					break;
				}
			}

			if(!$alreadyAssigned){
				$category_request->save();
			}

		}
		return true;
	}
	
	public function assignPersonel(){
		foreach ($this->listPersonel as $personel){
			$user_request = new UsersRequest();
			$user_request->request_id = $this->id;
			$user_request->user_id = $personel;
			
			$listAssigned = (new \yii\db\Query())
			->select(['user_id'])
			->from('users_request')
			->where(['request_id' => $this->id])
			->all();
			
			$alreadyAssigned = false;
			
			foreach($listAssigned as $assigned){
				if($personel === $assigned["user_id"]){
					$alreadyAssigned = true;
					break;
				}
			}

			if(!$alreadyAssigned){
				$user_request->save();
			}

		}
		return true;
	}
	
	public function removeCategories(){
		foreach ($this->listRemoveCategories as $category){
			$categoryRequest = CategoryRequest::findOne(['request_id'=>$this->id, 'category_id'=>$category]);
			$categoryRequest->delete();
		}
		return true;
	}
	
	public function removeAreas(){
		foreach ($this->listRemoveAreas as $area){
			$areaRequest = AreasRequest::findOne(['request_id'=>$this->id, 'area_id'=>$area]);
			$areaRequest->delete();
		}
		return true;
	}
	
}
