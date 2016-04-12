<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attached_files".
 *
 * @property string $request_id
 * @property string $url
 *
 * @property Request $request
 */
class AttachedFiles extends \yii\db\ActiveRecord
{

	//public $requestFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attached_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'url'], 'required'],
            [['request_id'], 'integer'],
			[['url'], 'string', 'max' => 100],
			[['url'], 'unique'],
			[['requestFile'], 'file', 'skipOnEmpty' => false, 'extensions'=>'pdf,png,jpg,jpeg,bmp,doc,docx'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request_id']);
    }
	
	/*public function beforeSave($insert){
		if(parent::beforeSave($insert)){
			$fileNameAttached = uniqid() . '.' . $this->requestFile->extension;
			$this->requestFile->saveAs('files/'.$fileNameAttached);
			$this->url = $fileNameAttached;
			
			return true;
			
		}
		return false;
	}*/
}
