Official UMI.CMS 3 development repository
=============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/badges/quality-score.png?b=dev&s=f8e7d72d796fb24475b431ffd024d92e60f7a16a)](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/?branch=dev)

## Contributing
Для участия в разработке необходимо ознакомиться с [правилами](CONTRIBUTING.md). 

## Клонирование репозитория
```sh
$ git clone git@github.com:Umisoft/umi.cms.3-dev.git
```

## Установка менеджера пакетов Composer
```sh
$ curl -sS https://getcomposer.org/installer | php
```

Если у вас не установлен curl:

```sh
$ php -r "readfile('https://getcomposer.org/installer');" | php
```

## Установка внешних зависимостей

```sh
$ php composer.phar install
```

##Настройка веб-сервера
Веб-сервер должен быть настроен так, чтобы DOCUMENT_ROOT указывал на директорию корень репозитория.

##Конфигурация БД
Необходимо создать базу данных и создать конфиг project/db.config.php на основе db.config.dist.php

##Установка демо-проектов
Для установки схем таблиц и данных проектов необходиом запустить в консоли

Для demo-* проектов
```sh
$ php ./bin/umi project:install http://localhost
```

Для других проектов
```sh
$ php ./bin/umi project:install http://localhost/{PROJECT_PREFIX}
```

Сейчас есть три demo с одними и теми же данными, но с разными шаблонизаторами:
- demo-twig twig-шаблонизатор (http://localhost/twig)
- demo-php php-шаблонизатор (http://localhost/, http://localhost/php)
- demo-xslt xslt-шаблонизатор

За подключение проектов отвечает конфиг project/projects.config.php.
Проекты доступны в двух локалях ru-RU и en-US. (http://localhost/twig/en, http://localhost/php/ru)

##Обновление

При обновлении dev-ветки, либо при merge dev-ветки в другую ветку необходимо обновлять внешние зависимости для backend и frontend.

1) Composer:
```sh
$ php composer.phar update
```
2) Bower:
```sh
cd installer/public/resources
bower update
```

Также, необходимо переустановить данные demo-проектов, запустив в браузере:
```
http://localhost/install
```
