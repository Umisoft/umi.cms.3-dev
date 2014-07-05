<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\model\object;

use umicms\orm\object\TCmsObject;

/**
 * Класс описывающий пункт меню.
 */
abstract class MenuItem extends BaseMenu
{
    /**
     * Тип объекта
     */
    const TYPE = 'menuItem';
    /**
     * Тип объекта
     * @var string $itemType
     */
    protected $itemType = 'menuItem';
    /**
     * Имя поля для хранения имени меню
     */
    const FIELD_NAME = 'name';

    /**
     * Возвращает ссылку элемента меню.
     * @return string
     */
    abstract public function getItemUrl();

    /**
     * Возвращает тип ссылки.
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }
}
 