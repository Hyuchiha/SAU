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
    public $startDate;
    public $endDate;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['startDate', 'date', 'format' => 'yyyy-MM-dd'], 'required'],
            // rememberMe must be a boolean value
            [['endDate', 'date', 'format' => 'yyyy-MM-dd'],'required'],
        ];
    }
}