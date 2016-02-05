<?php
/**
 * Created by PhpStorm.
 * User: Kev'
 * Date: 05/02/2016
 * Time: 01:13 PM
 */

namespace app\models;

use yii\base\Model;

class ReportForm extends Model{
    public $dateInit;
    public $dateFinish;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['dateInit', 'safe'], 'required'],
            // rememberMe must be a boolean value
            ['dateFinish', 'safe'],
        ];
    }
}