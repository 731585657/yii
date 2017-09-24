<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Center;
use yii\web\Controller;

class CenterController extends Controller{
    //收货管理  添加
    public function actionAddress(){
        $model= new Address();
        $id=\Yii::$app->user->identity->id;
        $users=Address::find()->where(['member_id'=>$id])->asArray()->all();
        //var_dump($user);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            //接收数据
            $model->load($request->post(),'');
            //var_dump($model);exit;
            if($model->validate()){
                //var_dump($model);exit;
                //得到当前登录用户的ID
                //var_dump($model->status);exit;
                $model->username=$model->name;
                $model->member_id=$id=\Yii::$app->user->identity->id;
                $model->status=$model->statuss;
                $model->tal=$model->tals;
                //var_dump($id);exit;
                //将所得到的地址拼接到一起保存到数据库;
                $model->province=$model->location_p;//省
                $model->city=$model->location_c;//市
                $model->area=$model->location_a;//县
                $model->address=$model->detailed;//详细地址
                $model->purpose=$model->location_p.$model->location_c.$model->location_a.$model->detailed;
                    $model->save();
                    return $this->redirect('address',301);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->renderPartial('address',['users'=>$users]);
    }

    //删除
    public function actionDel($id){
        $model=Address::findOne(['id'=>$id]);
       // var_dump($model);exit;
        $model->delete();
        return $this->renderPartial('address');
    }

}