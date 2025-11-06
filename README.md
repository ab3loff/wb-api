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
docker exec -it app composer install
docker exec -it app php artisan key:generate

```

### ДОСТУП К БД

#### Host: mysql-3caed0fc-abelon-b7da.e.aivencloud.com

#### Port: 17861

#### User: avnadmin

#### Password: AVNS_zepTucRKoOrnmR66V8n

Эти данные вбить в .env

Подключиться можно через MySQL Workbench





