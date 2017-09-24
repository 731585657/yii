<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_coun`.
 */
class m170911_031711_create_goods_day_coun_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_coun', [
            'day' => $this->dateTime()->comment('日期'),
            'count'=> $this->integer()->comment('商品数量'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_coun');
    }
}
