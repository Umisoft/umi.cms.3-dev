Official UMI.CMS 3 development repository
=============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/badges/quality-score.png?b=dev&s=f8e7d72d796fb24475b431ffd024d92e60f7a16a)](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/?branch=dev)

## Contributing
Для участия в разработке необходимо ознакомиться с [правилами](CONTRIBUTING.md). 

## Клонирование репозитория
```sh
$ git clone git@github.com:Umisoft/umi.cms.3-dev.git
```
## Установка инструментов для front-end

1) <a href="http://nodejs.org/download/">Скачать</a> и установить Node.JS.

2) Установка менеджера пактов "Bower":

```sh
$ npm install -g bower
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

```sh
$ cd installer/public/resources
```

```sh
$ bower install
```

##Настройка веб-сервера
Веб-сервер должен быть настроен так, чтобы DOCUMENT_ROOT указывал на директорию installer/public.
Пример конфигурации разных серверов: http://umi-framework.ru/documentation/get_started/skeleton.html#d5e89

##Конфигурация БД
Необходимо создать базу данных и создать конфиг configuration/db.config.php на основе db.config.dist.php

##Установка демо-проектов
Для запуска инсталлятора необходиом запустить в браузере http://localhost/install

Сейчас есть три демо-проекта с одними и теми же данными, но с разными шаблонизаторами:
- lite-twig twig-шаблонизатор (http://localhost/twig)
- lite-php php-шаблонизатор (http://localhost/, http://localhost/php)
- lite-xslt xslt-шаблонизатор (временно не рабочий)

За конфигурацию проектов и роутинг отвечает конфиг configuration/projects.config.php.
Проекты доступны в двух локалях ru-RU и en-US. (http://localhost/twig/en, http://localhost/php/ru)

На данный момент инсталлятор простой (install/controller/InstallController.php), он создает структуры таблиц
и заполняет демо-проекты данными через ORM. В ближайшем будущем все это будет работать через API моделей данных.
Все метаданные коллекций и формы для админки тоже задаются конфигами вручную, это не удобно, но временно. Модели данных будут делать все рутинные операции с конфигами, в том числе и генерить код.
