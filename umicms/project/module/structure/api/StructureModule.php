<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\api;

use umicms\module\BaseModule;
use umicms\project\module\structure\api\collection\InfoBlockCollection;
use umicms\project\module\structure\api\collection\LayoutCollection;
use umicms\project\module\structure\api\collection\MenuCollection;
use umicms\project\module\structure\api\collection\StructureElementCollection;
use umicms\project\module\structure\api\object\Layout;
use umicms\project\module\structure\api\object\StructureElement;

/**
 * Модуль для работы со структурой.
 */
class StructureModule extends BaseModule
{

    /**
     * Возвращает коллекцию для работы с элементами структуры.
     * @return StructureElementCollection
     */
    public function element()
    {
        return $this->getCollection('structure');
    }

    /**
     * Возвращает коллекцию для работы с шаблонами.
     * @return LayoutCollection
     */
    public function layout()
    {
        return $this->getCollection('layout');
    }

    /**
     * Возвращает коллекцию для работы с информационными блоками.
     * @return InfoBlockCollection
     */
    public function infoBlock()
    {
        return $this->getCollection('infoblock');
    }

    /**
     * Возвращает коллекцию для работы с настраиваемым меню.
     * @return MenuCollection
     */
    public function menu()
    {
        return $this->getCollection('menu');
    }

    /**
     * Возвращает API для работы с автогенерируемым меню структуры
     * @return AutoMenu
     */
    public function autoMenu()
    {
        return $this->getApi('umicms\project\module\structure\api\AutoMenu');
    }

    /**
     * Возвращает API для работы с настраиваемым меню
     * @return CustomMenu
     */
    public function customMenu()
    {
        return $this->getApi('umicms\project\module\structure\api\CustomMenu');
    }

    /**
     * Возвращает шаблон сетки для элемента.
     * @param StructureElement $element
     * @return Layout
     */
    public function getElementLayout(StructureElement $element)
    {
        if (!$element->layout) {
            return $this->layout()->getDefaultLayout();
        }

        return $element->layout;
    }

    /**
     * Возвращает страницу сайта по умолчанию.
     * @return StructureElement $element
     */
    public function getDefaultPage()
    {
        return $this->element()->getDefaultPage();
    }
}
