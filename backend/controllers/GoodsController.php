<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use yii\data\Pagination;
use backend\models\GoodsGallery;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $query= Goods::find();
        //搜索



        //实例化一个对象
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'PageSize'=>2
        ]);
        $request=\Yii::$app->request->get();
       // var_dump($request);exit;
            $models=Goods::find()->limit($pager->limit)->offset($pager->offset)->all();
        //var_dump($models);exit;
        return $this->render('index', ['models' => $models,'pager'=>$pager]);


    }

    //添加
    public function actionAdd()
    {
        $model = new Goods();
        $intros=new GoodsIntro();
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $intros->load($request->post());

            //var_dump($model);exit;

            //echo '<pre>';
            //var_dump($model);exit;
            //后台验证
            if($model->validate() && $intros->validate()){
                //保存货号到数据库
                //得到当前日期
                $day=date('Ymd');
                //var_dump($day);exit;
                //查询表里的数据
                $dayCount=GoodsDayCount::findOne(['day'=>$day]);
                //var_dump($dayCount);exit;
                //判断表里是否数据
                if($dayCount==null){
                    $dayCount = new GoodsDayCount();//货号
                    $dayCount->day=$day;
                    $dayCount->count=0;
                    $dayCount->save();
                }
                $model->create_time=time();
                $model->sn=date('Ymd',time()).sprintf("%04d",$dayCount->count+1);
                $model->save();
                //添加goods_intro标的数据
                $intros->goods_id=$model->id;
                $intros->save();
                $dayCount->count++;
                $dayCount->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
            //获取品牌数据
        $brands = Brand::find()->all();
        return $this->render('add', ['model' => $model, 'brands' => $brands,'intros'=>$intros]);
    }

    //修改
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $intros=GoodsIntro::findOne(['goods_id'=>$id]);
        $brands = Brand::find()->all();
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $intros->load($request->post());
            //后台验证
            if($model->validate() && $intros->validate()){
                $model->save();
                //保存商品简介
                $intros->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add', ['model' => $model, 'brands' => $brands,'intros'=>$intros]);

    }

    //删除
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        //var_dump($model);exit;
        //修改商品数量
        $model->delete();
        $time=date('Y-m-d',$model->create_time);
        $dayCount=GoodsDayCount::findOne(['day'=>$time]);
        //var_dump($dayCount);exit;
        //var_dump($time);exit;
        $dayCount->count=$dayCount->count-1;
        //根据时间判断这天有没有添加商品
        $dayCount->save();
        //删除商品详情
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        $intro->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);
    }

    //相册 第一步
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
       // var_dump($goods);exit;
        if($goods == null){
            \Yii::$app->session->setFlash('success','商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    //删除相册图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //七牛云
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    //var_dump($fileext);exit;
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    // $action->output['fileUrl'] = $action->getWebUrl();//输出图片的路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
//                    //将图片上传到七牛云  并且返回url地址
                    $config = [
                        'accessKey'=>'IWSUOezNZ3kKOvcfUtYIMsp0vFLUrQHuAhCb91kE',
                        'secretKey'=>'u1N5uEGpn8YwAjPQU9hmwIZ81EisEr6Rt3ZCe7bV',
                        'domain'=>'http://ow0evxet8.bkt.clouddn.com/',
                        'bucket'=>'0516php',
                        'area'=>Qiniu::AREA_HUADONG
                    ];



                    $qiniu = new Qiniu($config);
                    $key = $action->getWebUrl();
                    //上传文件的奥七牛云
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    //获取七牛云上的文件的url路径
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',

            ]
        ];
    }

}

