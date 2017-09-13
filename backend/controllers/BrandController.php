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
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends Controller{
    public function actionIndex(){
        $query=Brand::find();
        //显示列表
        //实例化一个分页对象
        $pager=new Pagination([
            'totalCount'=>$query->where(['>','status',-1])->count(),
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
            //var_dump($model);exit;
            //接收图片
            //$model->file=UploadedFile::getInstance($model,'file');
            //var_dump($model->file);exit;
            //后台验证
           if($model->validate()){
                //移动文件
                //$file='/upload/'.uniqid().'.'.$model->file->getExtension();
                //var_dump($file);exit;
                //得到文件的绝对路径
                //$model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                //var_dump($model->file);exit;
                //$model->logo=$file;
                //保存数据
               //var_dump($model);die;
                $model->save();
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
           // $model->logo=UploadedFile::getInstance($model,'logo');
            //var_dump($model->file);exit;
            //后台验证
            if($model->validate()){
//                if($model->logo){
//                    //移动文件
//                    $file='/upload/'.uniqid().'.'.$model->logo->getExtension();
//                    //var_dump($file);exit;
//                    //得到文件的绝对路径
//                    $model->logo->saveAs(\Yii::getAlias('@webroot').$file,false);
//                    //var_dump($model->file);exit;
//                    $model->logo=$file;
//                }
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
    public function actionDel(){
        //接收ajax传过来的id
        $id=\Yii::$app->request->post('id');
        //var_dump($id);exit;
        $model=Brand::findOne(['id'=>$id]);
        if($model){
            //修改status的值为-1不显示
            $model->status=-1;
            //保存
            $model->save(false);
           return "success";
        }
        return 'fail';
    }




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
        ];
    }

    //七牛云
    public function actionQiniu(){
        $config = [
            'accessKey'=>'IWSUOezNZ3kKOvcfUtYIMsp0vFLUrQHuAhCb91kE',
            'secretKey'=>'u1N5uEGpn8YwAjPQU9hmwIZ81EisEr6Rt3ZCe7bV',
            'domain'=>'http://ow0evxet8.bkt.clouddn.com/',
            'bucket'=>'0516php',
            'area'=>Qiniu::AREA_HUADONG
        ];



        $qiniu = new Qiniu($config);
        $key = '1.jpg';
        //上传文件的奥七牛云
        $file=\Yii::getAlias('@webroot/upload/1.jpg');
        $qiniu->uploadFile($file,$key);
        //获取七牛云上的文件的url路径
        $url = $qiniu->getLink($key);
        var_dump($url);exit;
    }

}