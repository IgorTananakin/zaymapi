<?php

use yii\db\Migration;

class m250919_182620_seed_users_table extends Migration
{
    public function safeUp()
    {

        $this->batchInsert('users', ['name', 'password'], [
            ['Иван Иванов', 'password123'],
            ['Петр Петров', 'secret456'],
            ['Мария Сидорова', 'qwerty789'],
            ['Анна Козлова', 'testpass'],
            ['Сергей Смирнов', 'hello123'],
            ['Екатерина Волкова', 'cat2024'],
            ['Дмитрий Орлов', 'eagle88'],
            ['Ольга Новикова', 'newpass'],
            ['Алексей Комаров', 'mosquito'],
            ['Наталья Лебедева', 'swan123'],
        ]);
    }

    public function safeDown()
    {
        echo "m250919_182620_seed_users_table cannot be reverted.\n";
        return false;
    }
}
