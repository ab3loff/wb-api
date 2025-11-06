первый опыт с докером

## Технологии

- PHP 8.0 + Laravel 10
- Docker & Docker Compose
- MySQL 8
-  Nginx
- Composer

### Установка

```bash
docker compose up -d --build
docker exec -it wb composer update
создать .env
docker exec -it wb php artisan key:generate

```

### ДОСТУП К БД

#### Host: mysql-3caed0fc-abelon-b7da.e.aivencloud.com

#### Port: 17861

#### DB_NAME: defaultdb

#### User: avnadmin

#### Password: AVNS_zepTucRKoOrnmR66V8n

Эти данные вбить в .env

Подключиться можно через MySQL Workbench


Кстати, в исходном коде у вас была ошибка с Eloquent.

На этот хостинг загружен проект, можно через него посмотреть данные по API, но только в браузере, т.к. бесплатный хост не дает просматривать через Postman
https://wb-api.infinityfree.me



