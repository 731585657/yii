<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 19:07
 */
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'是否显示',
        ];
    }
    public function rules(){
        //name  author_id  is_on_sale  intro       price  sn    logo
        return [
            [['name','intro','status','sort'],'required'],
        ];
    }
}