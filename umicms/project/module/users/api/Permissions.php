<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\users\api;


/**
 * API для работы с правами.
 */
class Permissions
{
    /**
     * Право на чтение.
     */
    const ALLOW_READ = 0b0001;
    /**
     * Право на создание.
     */
    const ALLOW_CREATE = 0b0010;
    /**
     * Право на изменение.
     */
    const ALLOW_UPDATE = 0b0100;
    /**
     * Право на удаление.
     */
    const ALLOW_DELETE = 0b1000;
    /**
     * Все права.
     */
    const ALLOW_ALL = 0b1111;



}
 