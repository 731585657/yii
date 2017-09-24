<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/18
 * Time: 14:18
 */
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Acrt;
use frontend\models\LoginForm;
use frontend\models\SmsDemo;
use yii\web\Controller;
use frontend\models\Member;
use yii\web\Cookie;



class MemberController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionRegister()
    {
        $model = new Member();
        $request = \Yii::$app->request;;
        if ($request->isPost) {
            $model->load($request->post(), '');
            //var_dump($model);
           // exit;
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '注册成功');
                return $this->renderPartial('login');
            }
        }

        return $this->renderPartial('register');
    }


    //登录
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            //var_dump($model);exit;
            if ($model->validate()) {
//                var_dump($model->rememberMe);exit;
                //取出username和数据库进行对比
                $user = Member::findOne(['username' => $model->username]);
                //var_dump($user);exit;
                //判断用户是否存在
                if (!$user) {
                    return $this->renderPartial('login');
                };
                //验证密码
                if (\Yii::$app->security->validatePassword($model->password, $user->password_hash)) {
                    //最后登录时间
                    $user->last_login_time = time();
                    //最后登录ip
                    $user->last_login_ip = \Yii::$app->request->userIP;
                    //保存到数据库
                    //判断是否勾选了自动登录
                    if ($model->rememberMe) {
                        \Yii::$app->user->login($user, 2 * 24 * 3600);
                        //echo 11111;exit;
                    } else {
                        \Yii::$app->user->login($user);
                    }
                    $user->save(false);
                    //登录后把cookie中数据写入数据表
                    $cookies = \Yii::$app->request->cookies;
                    //获取cookie中的购物车商品数据
                    $cookie_cart = $cookies->get('carts');
                    if($cookie_cart==null){
                        $carts = [];
                    }else{
                        $carts = unserialize($cookie_cart->value);
                    }
                    //循环遍历cookie购物车数据
                    foreach($carts as $goods_id=>$amount){
                        //查询数据库该用户名下是否有该商品
                        $cart = Acrt::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                        if($cart){
                            //如果数据表已经有这个商品,就合并cookie中的数量
                            $cart->amount+=$amount;
                            $cart->save();
                        }else{
                            //如果数据表没有这个商品,就添加这个商品到购物车表
                            $cart=new Acrt();
                            $cart->amount=$amount;
                            $cart->goods_id=$goods_id;
                            $cart->member_id=\Yii::$app->user->id;
                            $cart->save();
                        }
                    }
                    //---同步完后，清空cookie购物车数据----调用cookie对象的删除方法--
                    \Yii::$app->response->cookies->remove('carts');
                    return $this->redirect(['member/index']);
                } else {
                    //echo 222222;exit;
                    return $this->redirect(['user']);
                }

            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->renderPartial('login');
    }

    //验证用户是否存在
    //ajax验证用户唯一性
    public function actionValidateUser()
    {
        $username = \Yii::$app->request->get('username');
        $model = new Member();
        $user = $model->findone(['username' => $username]);
        if ($user) {
            return 'false';
        } else {

            return 'true';
        }
    }

    //首页
    public function actionIndex()
    {
        $categories = GoodsCategory::find()->all();
        $result = $this->getTree($categories, 0);
        return $this->renderPartial('index', ['menus' => $result]);
    }

    //列表
    public function actionList($id) {
        // 查询出当前分类和子级分类
        $cates = GoodsCategory::find()->where(['id'=>$id])->orWhere(['parent_id'=>$id])->all();
        // 取出当前分类和子级分类的id并放入$ids数组
        $ids = [];
        foreach ($cates as $cate) {
            $ids[] = $cate['id'];
        }
        $goods = Goods::find()->where(['in', 'goods_category_id', $ids])->all();
        return $this->renderPartial('list', ['goods'=>$goods]);
    }


    //商品详情
    public function actionGoods($id){
        $model=Goods::findOne(['id'=>$id]);
        //var_dump($model);exit;

        return $this->renderPartial('goods',['model'=>$model]);
    }

    //添加购物车
    public function actionAddcart($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            //判断cookie中是否有商品
            //先取出cookie里的数据来进行判断
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            //进行判断
            if($value){
                $carts=unserialize($value);
            }else{
                //没有数据
                $carts=[];
            }
            //检查cookie中是否有相同的数据
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id]=$amount;
            }
            //将数据存入数组

            //实例化cookie组件
            $cookies=\Yii::$app->response->cookies;
            //实例化一个cookie对象
            $cookie= new Cookie();
            $cookie->name='carts';
            //由于cookie不能存放数组 所以序列化
            $cookie->value=serialize($carts);
            $cookie->expire=time()+7*24*3600;
            //添加到cookie中
            $cookies->add($cookie);

        }else{
            //已登录 直接操作数据表
            //查询数据表是否已有该商品
            $cart=Acrt::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
            if($cart==null){
                //---没有 添加数据到数据表------------
                $cart=new Acrt();
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=\Yii::$app->user->id;
                $cart->insert();
                $cart->save();
            }else{
                //---已有该商品数据 修改数据到数据表------------
                $cart->updateall(['amount'=>$cart['amount']+$amount],['id'=>$cart['id']]);
                $cart->save();
            }
        }


        return $this->redirect(['cart']);


    }

    //显示购物车列表
    public function actionCart(){
        //判断是否登录
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            //注意因为存进去的时候是序列化的值 所以取出的时候需要反序列化
            $cart=$cookies->getValue('carts');
            if($cart){
                $carts=unserialize($cart);
                //var_dump($carts);exit;
            }else{
                //没有数据
                $carts=[];
            }
            //根据cookie中有的数据的id去商品表中查对应的数据来显示到页面,cookie中得数据无法进行显示
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
            //var_dump($carts);exit;
            return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts]);
        }else{
            //登录查询数据表中得数据
            $id=\Yii::$app->user->id;
            //var_dump($id);exit;
            $cartss=Acrt::find()->where(['member_id'=>$id])->all();
            $carts=[];
             foreach ($cartss as $cart){
                 //var_dump($cart);exit;
                 $carts[$cart->goods_id]=$cart->amount;
            }
            //var_dump($carts);exit;
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
            //var_dump($models);exit;
            return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts]);

        }

    }

        //Ajax修改商品数量
    public function actionAjax()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        ///var_dump($goods_id);exit;
        $amount = \Yii::$app->request->post('amount');
        //echo 333333;exit;
        //数据验证
        if(\Yii::$app->user->isGuest) {
            //判断cookie中是否有商品
            //先取出cookie里的数据来进行判断
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //进行判断
            if ($value) {
                $carts = unserialize($value);
            } else {
                //没有数据
                $carts = [];
            }
            //检查cookie中是否有相同的数据
            if (array_key_exists($goods_id, $carts)) {
                $carts[$goods_id] = $amount;
            }
            //将数据存入数组
            //实例化cookie组件
            $cookies = \Yii::$app->response->cookies;
            //实例化一个cookie对象
            $cookie = new Cookie();
            $cookie->name = 'carts';
            //由于cookie不能存放数组 所以序列化
            $cookie->value = serialize($carts);
            $cookie->expire = time() + 7 * 24 * 3600;
            //添加到cookie中
            $cookies->add($cookie);
        }else{
            //接收数据
            $goods_id = \Yii::$app->request->post('goods_id');
            ///var_dump($goods_id);exit;
            $amount = \Yii::$app->request->post('amount');
            //根据goods_id查出数据库的商品
            $cart=Acrt::findOne(['goods_id'=>$goods_id]);
            //var_dump($cart);exit;
            $cart->amount=$amount;
            $cart->save();

        }
    }

    //删除购物车
    public function actionDel(){
        //接收数据
        $goods_id=\Yii::$app->request->get('goods_id');
        //根据id查出对应数据
        $actr=Acrt::findOne(['goods_id'=>$goods_id]);
        $actr->delete();
    }



    //测试短信
    public function actionSms(){
        $code=rand(1000,9999);
        $demo = new SmsDemo(
            "LTAIIfWYpB5HOv5j",//AK
            "n3VnnDUenFHEgy1JjpIKFA9MYqkIOU"//SK
        );
        //echo "SmsDemo::sendSms\n";
        $response = $demo->sendSms(
            "张氏集团", // 短信签名
            "SMS_97865015", // 短信模板编号
            "17628043670", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
            )
        );
        if($response->Message == 'OK'){
            echo '发送成功';
        }else{
            echo '发送失败';
        }
        //接收ajax数据
        $tel=\Yii::$app->request->get('tel');
        //保存到redis中
        //创建一个redis
        $redis= new \Redis();
        //连接redis
        $redis->connect('127.0.0.1',6379);
        $redis->set('code_'.$tel,$code);

    }

    //验证短信
    public function actionValidateSms($tel,$sms)
    {
        $redis= new \Redis();
        //连接redis
        $redis->connect('127.0.0.1',6379);
        $code=$redis->get('code_'.$tel);
        if($code == null || $code != $sms){
            return 'false';
        }
        return 'true';

    }
    private function getTree($data, $pid) {
        $tree = [];
        foreach ($data as $k =>$v) {
            if ($v['parent_id'] == $pid){
                $v['parent_id'] = $this->getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

}