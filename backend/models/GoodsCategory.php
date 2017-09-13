<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/10
 * Time: 16:33
 */
namespace backend\models;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;

class GoodsCategory extends ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'],'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上级id',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    //获取zrtee的分类数据
    public static function getZNodes(){
        $top=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        $goodscategorys =GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
         //return array_unshift($goodscategorys,$top);
        //var_dump($goodscategorys);exit;
         return   ArrayHelper::merge([$top],$goodscategorys);
    }

    //异常提示信息
    public static function exceptionInfo($msg)
    {
        $infos = [
            'Can not move a node when the target node is same.'=>'不能修改到自己节点下面',
            'Can not move a node when the target node is child.'=>'不能修改到自己的子孙节点下面',
        ];
        return isset($infos[$msg])?$infos[$msg]:$msg;
    }
    //找子分类--
    public function getChildren(){
        return $this->hasMany(GoodsCategory::className(),['parent_id'=>'id']);
    }
    //找父分类
    public function getFather(){
        return $this->hasOne(GoodsCategory::className(),['parent_id'=>'id']);
    }

}