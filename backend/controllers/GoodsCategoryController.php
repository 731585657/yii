<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/10
 * Time: 15:59
 */
namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

class GoodsCategoryController extends Controller{
    public function actionIndex(){
        $models=GoodsCategory::find()->orderBy('tree ,lft ')->asArray()->all();
        //var_dump($models);exit;
        return $this->render('index',['models'=>$models]);
    }
    //添加商品分类
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            if($model->validate()){
                //判断添加顶级分类
                if($model->parent_id){
                    //子分类
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //测试ztree
    public function actionZtree(){
        //$this->layout=false;

        $goodscategorys=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //var_dump($goodscategorys);
        return $this->renderPartial('ztree',['goodscategorys'=>$goodscategorys]);

    }

    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        //var_dump($model);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                echo '<pre>';
                //var_dump($model);exit;
                //判断添加顶级分类
                if($model->parent_id){
                    //子分类
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        $model->maekeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $goodscategorys=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'goodscategorys'=>$goodscategorys]);
    }

    //删除
    public function actionDel($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
      $model->isLeaf();//判断是否有叶子节点
        $model->deleteWithChildren();


        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index']);
    }

    public function actions()
    {
        return [
            'vendor' => [
                'class' => 'kucha\ueditor\UEditorAction.php',
            ]
        ];
    }
}