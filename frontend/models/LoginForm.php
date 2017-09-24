<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/19
 * Time: 0:34
 */
namespace frontend\models;
use yii\base\Model;
use yii\web\IdentityInterface;

class LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['password','username'],'required'],
            ['rememberMe','string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '名称',
            'password' => '密码',

        ];
    }


}
