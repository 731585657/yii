<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 19:06
 */
namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends  Controller{
    //显示列表
    public function actionIndex(){
        $query=ArticleCategory::find();
        //实例化一个分页对象
        $requy=new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>2
        ]);
        $models=ArticleCategory::find()->where(['>','status','-1'])->limit($requy->limit)->offset($query->offset)->all();
        //var_dump($models);exit;
        return $this->render('index',['models'=>$models,'requy'=>$requy]);
    }
    //添加
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=\Yii::$app->request;
        //判断是否是post提交
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //后台验证
            if($model->validate()){
                //保存数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        //判断是否是post提交
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //后台验证
            if($model->validate()){
                //保存到数据库
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
         $model->save(false);
         \Yii::$app->session->setFlash('success','删除成功');
         return $this->redirect(['article-category/index']);
    }
}