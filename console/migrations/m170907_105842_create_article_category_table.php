<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170907_105842_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(50)->comment('文章名'),
            'intro'=> $this->text()->comment('简介'),
            'sort'=> $this->integer(11)->comment('排序'),
            'status'=> $this->integer(2)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
