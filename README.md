# Пример кода

Требования проекта:

PHP 8.2+

Mysql 8.0+

Redis

## Процесс разворачивания проекта.

### Склонировать проект 

``` bash
git clone git@github.com:argonianin/test.git
```

### Запустить composer install

``` bash
composer install
```

После установки, прописать в .env реквизиты доступа к бд и redis
В качестве источника кеширования указать redis
CACHE_STORE=redis

### Выполнить миграции

``` bash
php artisan migrate
```

Проект содержит тестовый набор данных. Три слота с разным объемом. Чтобы наполнить ими базу, выполните:

``` bash
php artisan db:seed
```

Для запуска проекта:

``` bash
php artisan serve
```

## Примеры curl запросов к api

### 1. Получение доступных слотов

``` bash
curl -X GET -v  http://127.0.0.1:8000/api/slots/availability
```

### 2. Создание холда

``` bash
curl -X POST -H "Idempotency-Key: hold_1" -v  http://127.0.0.1:8000/api/slots/1/hold
```

### 3. Подтверждение холда

``` bash
curl -X POST -H "Idempotency-Key: confirm_1" -v  http://127.0.0.1:8000/api/holds/1/confirm
```

### 4. Отмена холда

``` bash
curl -X DELETE -H "Idempotency-Key: delete_1" -v  http://127.0.0.1:8000/api/holds/1
```

