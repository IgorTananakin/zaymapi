<?php

use yii\db\Migration;

class m250919_175828_create_users_and_loans_tables extends Migration
{

    public function safeUp()
    {
        // Таблица пользователей
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'password' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Таблица займов
        $this->createTable('loans', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => $this->string(20)->defaultValue('new'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk-loans-user_id',
            'loans',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-loans-user_id', 'loans');
        $this->dropTable('loans');
        $this->dropTable('users');
    }

}
