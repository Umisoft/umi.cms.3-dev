<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\object;

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
 