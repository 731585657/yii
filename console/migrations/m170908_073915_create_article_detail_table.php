<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170908_073915_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->integer()->comment('文章id'),
            'content'=> $this->text()->comment('文章详情'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
