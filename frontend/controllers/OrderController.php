<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Acrt;
use frontend\models\Address;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;

class OrderController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionOrder(){
        if (\Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('success','请登录');
            return $this->redirect(['member/login']);
        }
            $model=new Order();
        //根据用户ID查询cart表中用户的所有goods_id数据----为数组
        $goods_ids=Acrt::find()->select('goods_id')->where(['member_id'=>\Yii::$app->user->id])->asArray()->column();
        //查询商品数量
        $cartss=Acrt::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //var_dump($cartss);exit;
        $sum=[];
        foreach ($cartss as $cart){
            //var_dump($cart);exit;
            $sum[$cart->goods_id]=$cart->amount;
        }
        //var_dump($sum);exit;
        $num = Acrt::find()->where(['member_id'=>\Yii::$app->user->id])->sum('amount');//商品总数
        //var_dump($num);exit;
        //查询数组中商品ID的商品数据
        $goods=Goods::find()->where(['in','id',$goods_ids])->all();
        //var_dump($goods);exit;
        // var_dump($goods);exit;
        $address = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();//地址数据
        //($address);exit;
        ///////////////提交/////////////////////
        //得到用户ID
        //$delivery_id =\Yii::$app->request->post('delivery_id');//送货方式id
        $transaction = \Yii::$app->db->beginTransaction();
        if(\Yii::$app->request->post() && $model->validate()) {
            try {
                $model->member_id = \Yii::$app->user->id;//用户ID
                $delivery_id =\Yii::$app->request->post('delivery');//送货方式id
                $address_id =\Yii::$app->request->post('address');//收货地址id
                $payment_id =\Yii::$app->request->post('pay');//支付方式id
                //var_dump($address_id);exit;
                $model->create_time=time();//创建时间
                //----收货人信息------
                $address=Address::findOne(['id'=>$address_id]);
                //根据$model->address_id 从地址表获取以下数据，并赋值给订单相应字段
                $model->name = $address->username;//收货人
                $model->province = $address->province;//省
                $model->city = $address->city;//市
                $model->area = $address->area;//县
                $model->address = $address->address;//详细地址
                $model->tal = $address->tal;//电话号码
                //-------配送方式---
                $model->delivery_id=$delivery_id;
                $model->delivery_name = Order::$deliveries[$delivery_id]['name'];
                $model->delivery_price = Order::$deliveries[$delivery_id]['price'];
                //---支付方式-----
                $model->payment_id=$payment_id;
                $model->payment_name = Order::$payments[$payment_id]['name'];
                //订单状态
                $model->status=1;
                $model->save(false);
                //（检查库存，如果足够）保存订单商品表
                //检查库存：购物车商品的数量和商品表库存对比，足够
                //---获取购物车数据----
                $carts=Acrt::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                $total=0;
                foreach ($carts as $cart) {
                    $goods = Goods::findOne(['id' => $cart->goods_id]);
                    $order_goods = new OrderGoods();
                    //--购物车商品数量《--》商品库存-------
                    if ($cart->amount <= $goods->stock) {
                        //$order_goods的其他属性赋值
                        $order_goods->order_id = $model->id;//订单id
                        $order_goods->goods_id = $goods->id;//商品id
                        $order_goods->goods_name = $goods->name;//商品名称
                        $order_goods->logo = $goods->logo;//图片
                        $order_goods->price = $goods->shop_price;//价格
                        $order_goods->amount = $cart->amount;//数量
                        $order_goods->total = $cart->amount * $goods->shop_price;//小计
                        $order_goods->save(false);
                        //扣减对应商品的库存
                        $goods->stock=$goods->stock -$cart->amount;
                        $goods->save(false);
                        //所有商品的金额
                        $total+=$order_goods->total;
                    } else {
                        //（检查库存，如果不够）
                        //抛出异常
                        throw new Exception('商品库存不足，无法继续下单，请修改购物车商品数量');
                    }
                }
                //下单成功后清除购物车
                Acrt::deleteAll(['member_id'=>\Yii::$app->user->id]);
                //order表的总金额 加上邮寄的金额
                $model->total=$total+Order::$deliveries[$delivery_id]['price'];
                $model->update(false,['total']);
                //提交事务
                $transaction->commit();
                return $this->redirect(['flow']);
            } catch (Exception $e) {
                //回滚
                $transaction->rollBack();
            }
        }
        return $this->renderPartial('addorder',['goods'=>$goods,'address'=>$address,'num'=>$num,'sum'=>$sum]);
    }

    public  function actionFlow(){
        return $this->renderPartial('flow');
    }

    public function actionIndex(){
        $model=new Order();
        return $this->render('index');
    }

}
