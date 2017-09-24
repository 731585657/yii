<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/16
 * Time: 11:14
 */
namespace backend\controllers;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

class  RoleController extends Controller{

        //列表
    public function actionIndex(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('index',['models'=>$models]);
    }

        //添加
    public function actionAdd(){
        $model= new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //var_dump($model);exit;
            //后台验证
            if ($model->validate()){
                $auth=\Yii::$app->authManager;
                //创建新角色
                $role=$auth->createRole($model->name);
                $role->description=$model->description;
                //保存角色
                $auth->add($role);
                //判断表单是否勾选了权限
                if($model->permissions){
                    //遍历得到对象里的每一个权限名称
                    foreach ($model->permissions as $permissionName){
                        $permission=$auth->getPermission($permissionName);
                        //给角色分配权限
                        $auth->addChild($role,$permission);
                    }

                }
                //跳转到列表
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($name){
        $model=new RoleForm();
        //实例化组件
        $model->scenario= RoleForm::SCENARIO_EDIT;
        $auth=\Yii::$app->authManager;
        /////////////////回显
        $role=$auth->getRole($name);
        $model->name=$role->name;
        $model->description=$role->description;
        //var_dump($role);exit;
        //获取权限
        $permission=$auth->getPermissionsByRole($name);
        //var_dump($permission);exit;
        $model->permissions=ArrayHelper::map($permission,'name','name');
        //修改
        $request=\Yii::$app->request;
        if ($request->isPost){
            //接收数据
            $model->load($request->post());
            //var_dump($model);exit;
            //判断是否修改了角色名称
                if($model->validate()){
                    //将接收到的值再赋值给角色 进行保存
                    $role->name=$model->name;
                    $role->description=$model->description;
                    //var_dump($role);exit;
                    $auth->update($name,$role);

                    //修改角色权限
                    $auth->removeChildren($role);//删除当前角色的所有权限 在进行赋值
                    if(is_array($model->permissions)){
                        //遍历得到对象里的每一个权限名称
                        foreach ($model->permissions as $permissionName){
                            $permission=$auth->getPermission($permissionName);
                            if($permission){
                                $auth->addChild($role,$permission);
                            }
                        }

                    }
                    //跳转到列表
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['index']);
                }


        }

        return $this->render('add',['model'=>$model]);

    }

    public function actionDel(){
        $name=\Yii::$app->request->post('name');
        $auth=\Yii::$app->authManager;
        $permission=$auth->getRole($name);
        if($permission){
            $auth->remove($permission);
            return "success";
        }
        return 'fail';
    }

}
