<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\object;

use umicms\orm\object\TCmsObject;

/**
 * Класс описывающий меню.
 */
class Menu extends BaseMenu
{
    /**
     * Тип объекта
     */
    const TYPE = 'menu';
    /**
     * Имя поля для хранения имени меню
     */
    const FIELD_NAME = 'name';
}
 