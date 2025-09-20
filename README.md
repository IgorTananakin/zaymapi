# тестовый проект микрозаймы API
Техническое задание web/testTask.pdf
## Описание проекта
REST API для обработки заявок на микрозаймы с вероятностной системой одобрения 10% шанс.

## Ветка master c docker. Ветка no-docker без docker только проект

## Технологии
Docker
Docker Compose
Nginx
composer
Yii2
PostgreSQL

## Основные функции
- Подача заявок на займ
- Автоматическая обработка заявок
- Вероятностная система одобрения (10%)
- Проверка: у пользователя может быть только одна одобренная заявка

## Установка проекта
1. Клонируйте проект

2. поднятия контейнеров
```bash
docker-compose up -d --build
```bash

3. проверка подключения к базе файл /config/web.php
```bash
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=loans',
    'username' => 'user',
    'password' => 'password',
    'charset' => 'utf8',
];
```

4. Примените миграции базы данных
```bash
docker-compose exec app php yii migrate
```

5. Перед запуском проверить корневую директорию для веб-сервера public/web убедиться, а то запрос может не отработать без web/

6. Если возникают ошибки с правами
```bash
docker-compose exec app chmod -R 777 web/assets
docker-compose exec app chmod -R 777 runtime
docker-compose exec app chown -R www-data:www-data web/assets
docker-compose exec app chown -R www-data:www-data runtime
```
## запросы

```bash
curl --location 'http://localhost:8000/web/requests' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'user_id=1' \
--data-urlencode 'amount=5000' \
--data-urlencode 'term=15'
```
Ответ 201:

```bash
json
{
  "result": true,
  "id": 1
}
```

Запуск обработки:

```bash
curl --location 'http://localhost:8000/web/processor?delay=5'
```

Ответ (200):

```bash
json
{
  "result": true
}
```

4. Проверка результатов в базе

## Статусы заявок
new - новая заявка

approved - одобрена (10% )

rejected - отклонена