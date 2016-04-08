<?php
/**
 * Created by PhpStorm.
 * User: Kev'
 * Date: 05/02/2016
 * Time: 01:13 PM
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class importForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $csv;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['csv'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }

    public function upload()
    {
        $this->csv->saveAs('uploads/' . $this->csv->baseName . '.' . $this->csv->extension);
        return true;
    }
}