<?php
// Эти константы должны быть определены ДО подключения любых конфигов Yii
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// Подключаем автозагрузчик Composer
require __DIR__ . '/vendor/autoload.php';

// Подключаем класс Yii
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Создаем конфиг с ОБЯЗАТЕЛЬНЫМ параметром 'id'
$config = [
    'id' => 'test-app', // Обязательный параметр
    'basePath' => __DIR__,
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=loans',
            'username' => 'user',
            'password' => 'passwotd',
            'charset' => 'utf8',
        ]
    ]
];

// Создаем экземпляр приложения
$app = new yii\web\Application($config);

// Проверяем подключение
try {
    Yii::$app->db->open();
    echo "Подключение к PostgreSQL успешно!";
} catch (\yii\db\Exception $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}