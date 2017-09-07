<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 15:59
 */
namespace backend\models;
use yii\db\ActiveRecord;

class Brand extends  ActiveRecord{
    public $file;
    public function attributeLabels(){
        return [
            'name'=>'品牌名',
            'intro'=>'简介',
            'file'=>'图片',
            'sort'=>'排序',
            'status'=>'是否显示',
        ];
    }

    //验证规则
    public function rules(){
        //name  author_id  is_on_sale  intro       price  sn    logo
        return [
            [['name','intro','status','sort'],'required'],
            ['file','file','extensions'=>['jpg','png','gif']],
        ];
    }
}