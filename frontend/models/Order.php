<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tal
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 255],
            [['tal'], 'string', 'max' => 11],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收件人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tal' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付ID',
            'payment_name' => '支付方式名称',
            'total' => '订单价格',
            'status' => '状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }

    //定义送货方式
    public static $deliveries=[
        1=>['name'=>'普通快递送货上门','price'=>'10','detail'=>'速度一般，服务一般，价格便宜'],
        2=>['name'=>'特快专递','price'=>'15','detail'=>'速度快，服务好，价格贵'],
        3=>['name'=>'加急快递送货上门','price'=>'20','detail'=>'速度快，服务好，价格贵'],
        4=>['name'=>'EMS','price'=>'10','detail'=>'速度一般，服务一般，价格贵，国内任何地址都可以送到'],
    ];
    //定义支付方式
    public static $payments=[
        1=>['name'=>'货到付款','detail'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['name'=>'在线支付','detail'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['name'=>'上门自提','detail'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['name'=>'邮局汇款','detail'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
    ];

    public function getAddress(){
        return $this->hasMany(Address::className(),['member_id'=>'member_id']);
    }


}
