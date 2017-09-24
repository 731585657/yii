<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;

class RbacController extends \yii\web\Controller
{
    public function actionIndex()
    {
       //先实例化组件
        $auth=\Yii::$app->authManager;
        $permissions=$auth->getPermissions();
       // $permissions=$auth->
        return $this->render('index',['permissions'=>$permissions]);
    }

    //添加
    public function actionAdd(){
        $model= new PermissionForm();
        $request=\Yii::$app->request;
        $model->scenario = PermissionForm::SCENARIO_ADD;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //实例化authM
                $auth=\Yii::$app->authManager;
                //添加权限
                $permission= $auth->createPermission($model->name);
                $permission->description=$model->description;
                    //创建权限
                $auth->add($permission);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($name){
        $model= new PermissionForm();
        //var_dump($name);exit;
        $model->scenario = PermissionForm::SCENARIO_EDIT;
        $auth=\Yii::$app->authManager;
        $permission=$auth->getPermission($name);
        $model->name=$permission->name;
        $model->description=$permission->description;
        //var_dump($model);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //判断是否修改了权限名称
            if($name == $model->name){
                if($model->validate()){
                    //不修改名称就只将描述修改
                    $permission->description=$model->description;
                    $auth->update($name,$permission);
                  \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['index']);
                }
            }else{
                if($model->validate()){
                    $permission->name=$model->name;
                    $permission->description=$model->description;
                    $auth->update($name,$permission);
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['index']);
                }

            }

        }
        return $this->render('add',['model'=>$model]);

    }

    //删除
       public function actionDel(){
        $name=\Yii::$app->request->post('name');
        $auth=\Yii::$app->authManager;
        $permission=$auth->getPermission($name);
        if($permission){
            $auth->remove($permission);

            return "success";
        }
        return 'fail';
    }

    public function behaviors(){
           return [
             'rbac'=>[
                 'class'=>RbacFilter::className(),
             ]
           ];
    }

}

