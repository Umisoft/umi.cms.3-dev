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
 * Класс описывающий пункт меню.
 */
abstract class MenuItem extends BaseMenu
{
    /**
     * Тип объекта
     */
    const TYPE = 'menuItem';
    /**
     * @var string $itemType тип элемента меню
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
 