<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/8
 * Time: 15:42
 */
namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    //显示列表
    public function actionIndex()
    {
        $query = Article::find();
        //实例化一个分页对象
        $requy = new Pagination([
            'totalCount' => $query->where(['>', 'status', -1])->count(),
            'PageSize' => 2
        ]);
        //var_dump($models);exit;
        $models = Article::find()->where(['>', 'status', -1])->limit($requy->limit)->offset($requy->offset)->all();
        return $this->render('index', ['models' => $models, 'requy' => $requy]);
    }

    //添加
    public function actionAdd()
    {
        $model = new Article();
        $articled = new ArticleDetail();
        $request = \Yii::$app->request;
        //判断是否是post提交
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //var_dump($model);exit;
            $articled->load($request->post());
            //后台验证
            if ($model->validate() && $articled->validate()) {
                $model->create_time = time();
                $model->save(false);
                $articled->article_id = $model->id;
                $articled->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article/index']);
            }
        }
        //获取文章分类id
        $article = ArticleCategory::find()->all();
        return $this->render('add', ['model' => $model, 'article' => $article, 'articled' => $articled]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = Article::findOne(['id' => $id]);
        //var_dump($model);exit;
        $article = ArticleCategory::find()->all();
        //var_dump($article);exit;
        $articled = ArticleDetail::findOne(['article_id' => $id]);

        $request = \Yii::$app->request;
        //判断是否是post提交
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //var_dump($model);exit;
            $articled->load($request->post());
            //后台验证
            if ($model->validate() && $articled->validate()) {
                $model->create_time = time();
                $model->save(false);
                //$articled->article_id = $model->id;
                //var_dump($articled->article_id);exit;
                $articled->save(false);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article/index']);
            }
        }
        //var_dump($articled);exit;
        return $this->render('add', ['model' => $model, 'article' => $article, 'articled' => $articled]);
    }

    //查看详情
    public function actionShow($id)
    {
        $model = ArticleDetail::findOne(['article_id' => $id]);
        //var_dump($model);exit;
        return $this->render('show', ['model' => $model]);
    }

    //删除
    public function actionDel()
    {
        //接收ajax传过来的id
        $id = \Yii::$app->request->post('id');
        //var_dump($id);exit;
        $model = Article::findOne(['id' => $id]);
        //var_dump($model);exit;
        if ($model) {
            //修改status的值为-1不显示
            $model->status = -1;
            //var_dump( $model->status);exit;
            //保存
            $model->save();
           // var_dump($model->save());exit;
            return "success";
        }
        return 'fail';
    }
}