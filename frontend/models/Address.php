<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/19
 * Time: 15:11
 */
namespace frontend\models;
use yii\db\ActiveRecord;

class  Address extends  ActiveRecord{

    public $name;//名称
    public $location_p;//省
    public $location_c;//城市
    public $location_a;//区域
    public $detailed;//详细地址
    public $tals;//电话
    public $statuss;//状态



    public function rules()
    {
        return [
            [['location_a','location_c','location_p','name','tals','detailed'],'required'],
            [['province','city','area','address'],'string'],
            ['statuss','string'],
        ];
    }

}