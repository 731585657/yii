<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/13
 * Time: 22:43
 */
namespace backend\controllers;
use yii\web\Controller;
use yii\web\Cookie;

class  Day3Controller extends Controller{
    public function actionCookie(){
//        $cookies=\Yii::$app->response->cookies;
//        $cookie=new Cookie([
//           'name'=>'name' ,
//            'value'=>'lisi',
//        ]);
//        $cookies->add($cookie);
        $cookies=\Yii::$app->request->cookies;
        var_dump($cookies->getValue('name'));
    }
}