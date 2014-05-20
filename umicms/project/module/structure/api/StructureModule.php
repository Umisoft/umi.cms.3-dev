<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umicms\module\BaseModule;
use umicms\project\module\structure\api\collection\InfoBlockCollection;
use umicms\project\module\structure\api\collection\LayoutCollection;
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
     * Возвращает API для работы с автогенерируемым меню структуры
     * @return AutoMenu
     */
    public function menu()
    {
        return $this->getApi('umicms\project\module\structure\api\AutoMenu');
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
