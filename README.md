# Symfony Docker installer

## About
Symfony 6.3.*@dev
php-fpm 8.1
Mysql latest
nginx latest

## Installation
### Step 1
Copy `.env.install.dist` file to `.env.install`

Change `PROJECT_NAME` in `.env.install`

### Step 2
```console
docker-compose --env-file .env.install up --build
```
OR
```console
/bin/bash project.sh -i
```

This may take a few minutes. Wait for completion.

### Step 3
delete install files:
.env.install
.env.install.dist
in docker-compose.yaml delete section symfony-install:
Profit


#домашне_завдання 13
1) Встановити Сімфоні з репозиторію https://github.com/UFO-CMS/symfony-docker-installer і підмінити репозиторій на репозиторій вашого проєкту
1. git clone git@github.com:UFO-CMS/symfony-docker-installer.git
2. git remote set-url origin git@github.com:{ВАШЕ_ІМʼЯ}/{ВАШ_РЕПОЗИТОРІЙ}.git
3. виконати дії з README

перед початком роботи виконайте
1. docker compose up -build

1. docker exec -i -t "php_web_code" /bin/bash
2. composer install
3. php bin/doctrine orm:schema-tool:update --force

php bin/console make:migration
php bin/console doctrine:migrations:migrate

## Use

For use
```console
docker-compose --env-file .env.local up
```
OR
```console
/bin/bash project.sh
```
