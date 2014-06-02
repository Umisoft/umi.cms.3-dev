Official UMI.CMS 3 development repository
=============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/badges/quality-score.png?b=dev&s=f8e7d72d796fb24475b431ffd024d92e60f7a16a)](https://scrutinizer-ci.com/g/Umisoft/umi.cms.3-dev/?branch=dev)

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