<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/10
 * Time: 16:30
 */
namespace backend\models;
use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;
class CategoryQuery extends ActiveQuery{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}