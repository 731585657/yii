<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/16
 * Time: 11:15
 */
namespace backend\models;
use yii\base\Model;

class RoleForm extends  Model{
    //定义字段
    public $name;
    public $description;
    public $permissions;

    //定义一个常量来保存情景
    const SCENARIO_ADD='add';
    const SCENARIO_EDIT='edit';


    //定义规则
    public function rules(){
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName','on'=>[self::SCENARIO_ADD]],
            ['name','validateEditName','on'=>[self::SCENARIO_EDIT]],
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permissions'=>'权限名称'
        ];

    }


    public function validateName(){
        if(\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }

    public function validateEditName() {

        if(\Yii::$app->request->get('name') != $this->name){
            if(\Yii::$app->authManager->getRole($this->name)){
                $this->addError('name','角色已经存在');
            }
        }

    }
    //在表单里显示权限的多选内容
    public static function getPermissionItems(){
        $permissions=\Yii::$app->authManager->getPermissions();
        //转换成表单相对应的格式  用数组来装
        $items=[];
        //遍历出所有的权限数据
        foreach ($permissions as $permission){
            $items[$permission->name]=$permission->description;
        }
        return $items;
    }

}