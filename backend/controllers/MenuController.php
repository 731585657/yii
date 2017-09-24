<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;

class MenuController extends \yii\web\Controller
{
    public function actionIndex() {
        $models=Menu::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    //添加
    public  function actionAdd(){
        $model=new  Menu();
        $auth=\Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        $top=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        $rows=Menu::find()->where(['=','parent_id',0])->all();
          $rows=ArrayHelper::merge([$top],$rows);
        //////////////////////////////
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            // var_dump($model);exit;
            if($model->validate()){
                //保存到数据库
                $model->save();
                return $this->redirect(['menu/index']);

            }
        }

        return $this->render('add',['model'=>$model,'rows'=>$rows,'permissions'=>$permissions]);
    }

    //修改
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $rows=Menu::find()->where(['=','parent_id',0])->all();
        //var_dump($rows);exit;
        $auth=\Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        //////////////////////////
        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model,'rows'=>$rows,'permissions'=>$permissions]);
    }

    //删除
    public function actionDel()
    {
        $id=\Yii::$app->request->post('id');
        $model = Menu::findOne(['id' => $id]);
        if($model){
            $model->delete();
            return 'success';
        }
           return 'fail';
    }




}
