<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170913_035706_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username'=> $this->string()->comment('用户名'),
            'password'=> $this->string()->comment('密码'),
            'email'=> $this->string()->comment('邮箱'),
            'status'=> $this->string(1)->comment('状态'),
            'last_login_time'=> $this->integer()->comment('最后登录时间'),
            'last_login_ip'=> $this->integer()->comment('登录ip'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
