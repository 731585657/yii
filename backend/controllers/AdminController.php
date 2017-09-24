<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;

use backend\models\LoginForm;
use backend\models\PassForm;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class AdminController extends \yii\web\Controller
{
    //登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //var_dump($model);exit;
            //后台验证
            if ($model->validate()) {
                //根据username查出相同数据
                $user = Admin::findOne(['username' => $model->username]);
                //var_dump($user);exit;
                if($user){
                    //判断密码
                    if(\Yii::$app->security->validatePassword($model->password,$user->password_hash)){
                                $user->last_login_time=time();
                                //var_dump($user->last_login_time);exit;
                                $user->last_login_ip=\Yii::$app->request->userIP;
                                //var_dump($user->last_login_ip);exit;
                                $user->save(false);
                                if($model->rememberMe == 1){
                                    \Yii::$app->user->login($user,+2*24*3600);
                                }
                        return $this->redirect(['admin/index']);

                    }else{
                        \Yii::$app->session->setFlash('success','密码错误');
                        return $this->redirect(['admin/login']);
                    }
                }else{
                    \Yii::$app->session->setFlash('success','用户名不存在');
                    return $this->redirect(['admin/login']);
                }

            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    //退出
    public function actionLogout(){

        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','退出成功');
        return $this->redirect(['admin/login']);
    }


    //列表
    public function actionIndex()
    {
        if(\Yii::$app->user->identity){
            $models=Admin::find()->all();
            return $this->render('index',['models'=>$models]);

        }else{
            return $this->redirect(['admin/login']);
        }




    }

   //添加
    public function actionAdd(){
        //判断是否登录
        $model=new Admin();
        $auth=\Yii::$app->authManager;
        $model->scenario = Admin::SCENARIO_ADD;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model->permissions);exit;
            //var_dump($auth->getRole($model->permissions));exit;
            if($model->validate()){
                $model->save();
                //判断传过来的角色是否是数组
                if(is_array($model->permissions)){
                    foreach ($model->permissions as $rolesName){
                        $permission=$auth->getRole($rolesName);
                        //var_dump($permission);exit;
                        if ($permission){

                            $auth->assign($permission,$model->attributes['id']);
                        }
                    }
                }
                //var_dump($model->getErrors());exit;
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);



    }

    //修改
    public function actionEdit($id){
        $auth=\Yii::$app->authManager;
        if(\Yii::$app->user->identity){
            $model=Admin::findOne(['id'=>$id]);
            $request=\Yii::$app->request;
            if($model == null){
                throw new NotFoundHttpException('用户不存在');
            }
            if($request->isPost){
                //接收数据
                $model->load($request->post());
                if($model->validate()){
                    $model->save();
                    //判断传过来的角色是否是数组
                    if(is_array($model->permissions)){
                        $auth->revokeAll($id);//撤销
                        foreach ($model->permissions as $rolesName){
                            $permission=$auth->getRole($rolesName);
                            //var_dump($permission);exit;
                            if ($permission){

                                $auth->assign($permission,$model->attributes['id']);
                            }
                        }
                    }
                    //var_dump($model->getErrors());exit;
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['admin/index']);
                }
            }
            //回显角色  根据id找角色

            $roles=$auth->getRolesByUser($id);
            $model->permissions=array_keys($roles);
            return $this->render('add',['model'=>$model]);
        }else{
            \Yii::$app->session->setFlash('success','没有登录');
            return $this->redirect(['admin/login']);
        }

    }


    //删除
    public function actionDel($id){
        //判断是否登录
        if(\Yii::$app->user->identity){
            $model=Admin::findOne(['id'=>$id]);
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['admin/index']);
        }else{
            \Yii::$app->session->setFlash('success','没有登录');
            return $this->redirect(['admin/login']);
        }


    }


    //修改自己密码
    public function actionPassword(){
        $model=new PassForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
               // echo 111;exit;
                $admin = \Yii::$app->user->identity;
                //var_dump($admin);exit;
                $admin->password = $model->passwords;
                $admin->save();
                return $this->redirect(['admin/index']);

            }
        }
        return $this->render('pass',['model'=>$model]);
    }

//    public function behaviors(){
//        return [
//          'rbac'=>[
//              'class'=>RbacFilter::className(),
//              'except'=>['logout','login','error']
//          ]
//        ];
//    }
}
