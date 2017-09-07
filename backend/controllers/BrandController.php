<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 15:58
 */
namespace backend\controllers;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class BrandController extends Controller{
    public function actionIndex(){
        $query=Brand::find();
        //显示列表
        //实例化一个分页对象
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'PageSize'=>2
        ]);
        $models=Brand::find()->where(['>','status',-1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model= new Brand();
        $request=\Yii::$app->request;
        if ($request->isPost){
            //接收数据
            $model->load($request->post());
            //接收图片
            $model->file=UploadedFile::getInstance($model,'file');
            //var_dump($model->file);exit;
            //后台验证
            if($model->validate()){
                //移动文件
                $file='/upload/'.uniqid().'.'.$model->file->getExtension();
                //var_dump($file);exit;
                //得到文件的绝对路径
                $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                //var_dump($model->file);exit;
                $model->logo=$file;
                //保存数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
               return $this->redirect(['brand/index']);
            }
        }
        //显示列表
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
       // var_dump($model);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->file=UploadedFile::getInstance($model,'file');
            //var_dump($model->file);exit;
            //后台验证
            if($model->validate()){
                if($model->file){
                    //移动文件
                    $file='/upload/'.uniqid().'.'.$model->file->getExtension();
                    //var_dump($file);exit;
                    //得到文件的绝对路径
                    $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                    //var_dump($model->file);exit;
                    $model->logo=$file;
                }
                //保存数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model=Brand::findOne(['id'=>$id]);
        //修改status的值为-1不显示
        $model->status=-1;
        //保存
        $model->save(false);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
    }
}