# Desafio backend CONVICTI Tecnologia

![License](https://img.shields.io/badge/license-MIT-green) ![PHP](https://img.shields.io/badge/php-8.2-blue) ![Laravel](https://img.shields.io/badge/laravel-10-red)

O objetivo geral da API do sistema é servir como uma ferramenta de controle regional e nacional de vendas. Ela proporciona uma interface onde os vendedores, através de seus celulares, possam efetuar os lançamentos das vendas de forma eficiente e segura.

## Features
- Login
- Listar vendas (mobile e web)
- Salvar vendas 

## Installation

Docker:

```sh
docker compose up --build 
docker ps  
docker exec -it {container_id} bash
cp .env.example .env
php artisan key:generate
composer install

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

php artisan migrate
php artisan db:seed --class=DatabaseSeed
php artisan queue:work

```


Run tests:
```sh
Copie o valor da APP_KEY da .env para a .env.testing
vendor/bin/phpunit --testsuite Feature
```

## Routes

| Route | Method |
| ------ | ------ |
| /api/login | POST |
| /api/get-sales | GET |
| /api/mobile/get-sales | GET |
| /api/mobile/place-sales | POST |

## API Documentation

```sh
http://localhost:8000/request-docs

```


## License

MIT


