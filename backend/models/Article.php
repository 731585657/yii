<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/8
 * Time: 15:44
 */
namespace backend\models;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public function attributeLabels(){
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'article_category_id'=>'文章分类',
            'sort'=>'排序',
            'status'=>'是否显示',
        ];
    }
    public function rules(){
        //name  author_id  is_on_sale  intro       price  sn    logo
        return [
            [['name','intro','status','sort','article_category_id'],'required'],
        ];
    }
    //建立文章与文章分类的关系
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}