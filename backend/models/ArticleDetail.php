<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/8
 * Time: 16:31
 */
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{
    public function attributeLabels(){
        return [
            'content'=>'文章内容',

        ];
    }
    public function rules(){
        //name  author_id  is_on_sale  intro       price  sn    logo
        return [
            ['content','required'],
        ];
    }
}