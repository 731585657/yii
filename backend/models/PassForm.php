<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/15
 * Time: 11:30
 */
namespace backend\models;
use yii\base\Model;

class PassForm extends Model{
    //定义字段
    public $password;//旧密码
    public $passwords;//新密码
    public $repassword;//确认密码

    //定义规则
    public function rules(){
        return [
            [['password','passwords','repassword'],'required'],
            //['repassword','compare','compareValue'=>'passwords','message'=>'两次密码必须一致'],
            ['repassword','compare','compareAttribute'=>'passwords','message'=>'两次密码必须一致'],
            ['password','validatePassword'],
        ];
    }

    public function attributeLabels(){
        return [
            'password'=>'旧密码',
            'passwords'=>'新密码',
            'repassword'=>'确认密码',

        ];
    }
    //验正密码是否相同
    public function validatePassword(){
        //只验证并不正确的情况下
        if(!\Yii::$app->security->validatePassword($this->password,\Yii::$app->user->identity->password_hash)){
            //echo 1111;exit;
            //var_dump($this->password);exit;
            //var_dump(\Yii::$app->user->identity->password_hash);exit;
            $this->addError('password','密码不正确');
        };
    }

}