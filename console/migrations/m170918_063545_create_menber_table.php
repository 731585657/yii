<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menber`.
 */
class m170918_063545_create_menber_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menber', [
            'id' => $this->primaryKey(),
            'username'=> $this->string()->comment('名称'),
            'auth_key'=> $this->string()->comment('cookie'),
            'password_hash'=> $this->string()->comment('密码'),
            'email'=> $this->string()->comment('邮箱'),
            'tel'=> $this->char(11)->comment('电话'),
            'last_login_time'=> $this->integer()->comment('最后登录时间'),
            'last_login_ip'=> $this->string()->comment('最后登录IP'),
            'status'=> $this->integer(1)->comment('状态'),
            'created_at'=> $this->integer()->comment('创建时间'),
            'updated_at'=> $this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menber');
    }
}
