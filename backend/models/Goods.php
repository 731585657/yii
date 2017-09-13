<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $create_time
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock','name', 'is_on_sale', 'status', 'sort'], 'required'],
            [['market_price', 'shop_price'], 'number'],
            ['sn', 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'logo图片',
            'goods_category_id' => '商品分类ID',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'create_time' => '添加时间',
            'is_on_sale' => '是否在售(1在售 0下架)',
            'status' => '状态(1正常  0回收)',
            'sort' => '排序',
            'view_times' => '浏览次数',
        ];
    }
    //建立与 商品每日添加数的联系
    public function getGoodsDayCount(){
        return $this->hasOne(GoodsDayCount::className(),['create_time'=>'day']);
    }
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);//hasMany 返回多个对象 用数组封装
    }
    /*
    * 商品和相册关系 1对多
    */
    public function getGalleries()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);//hasMany 返回多个对象 用数组封装
    }

}
