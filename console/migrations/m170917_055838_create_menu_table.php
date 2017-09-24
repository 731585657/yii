<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170917_055838_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=> $this->string()->comment('菜单名称'),
            'parent_id'=> $this->integer()->comment('上级菜单'),
            'route'=> $this->string()->comment('路由'),
            'soft'=> $this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
