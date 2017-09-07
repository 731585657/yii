<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170907_071220_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(50)->comment('品牌名'),
            'intro'=> $this->text()->comment('简介'),
            'logo'=> $this->string()->comment('品牌图片'),
            'sort'=> $this->integer()->comment('排序'),
            'status'=> $this->integer(2)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
