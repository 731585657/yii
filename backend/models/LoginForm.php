<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/13
 * Time: 18:27
 */
namespace backend\models;
use yii\base\Model;

class  LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe;
    public function rules()
    {
        return [
            [['username','password'], 'required'],
            ['rememberMe','string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe'=>'自动登录',
        ];
    }

}