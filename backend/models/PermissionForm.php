<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/15
 * Time: 14:30
 */
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    //定义一个常量来保存情景
    const SCENARIO_ADD='add';
    const SCENARIO_EDIT='edit';

    public function rules(){
        return [
          [['name','description'],'required'],
            ['name','validateName','on'=>[self::SCENARIO_ADD]],
            ['name','validateEditName','on'=>[self::SCENARIO_EDIT]],
        ];
    }
    //自定义规则
    public function validateName(){
        if(\Yii::$app->authManager->getPermission($this->name)){
             $this->addError('name','权限已存在');
        }
    }

    public function validateEditName()
    {
        if (\Yii::$app->request->get('name') != $this->name) {
            if (\Yii::$app->authManager->getPermission($this->name)) {
                $this->addError('name', '权限已存在');
            }
        }
    }
    public function attributeLabels(){
        return [
            'name'=>'权限名称',
            'description'=>'描述',
        ];

    }
}