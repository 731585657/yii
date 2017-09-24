<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170919_082848_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username'=> $this->string()->comment('收货人'),
            'Purpose'=> $this->string()->comment('地址'),
            'status'=> $this->integer(2)->comment('默认地址'),
            'member_id'=> $this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
