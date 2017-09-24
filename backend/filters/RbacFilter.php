<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/17
 * Time: 21:34
 */
namespace backend\filters;
use Prophecy\Exception\Prediction\FailedPredictionException;
use yii\base\ActionFilter;
use yii\web\NotFoundHttpException;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        //return \Yii::$app->user->can($action->uniqueId);
        //判断用户是否登录 没有就跳转到登录界面
        if (\Yii::$app->user->isGuest) {
            return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
        }
        //没有权限显示提示页面
        if (!\Yii::$app->user->can($action->uniqueId)) {
            throw  new NotFoundHttpException('没有权限访问');
        }
        //return true;
        //return false;
        return parent::beforeAction($action);
    }
}
