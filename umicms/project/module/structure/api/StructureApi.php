<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;
use umicms\project\module\structure\api\collection\LayoutCollection;
use umicms\project\module\structure\api\collection\StructureElementCollection;
use umicms\project\module\structure\api\object\Layout;
use umicms\project\module\structure\api\object\StructureElement;

/**
 * API для работы со структурой.
 */
class StructureApi extends BaseComplexApi implements IPublicApi, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * @var StructureElement $currentElement
     */
    protected $currentElement;

    /**
     * Возвращает коллекцию для работы с элементами структуры.
     * @return StructureElementCollection
     */
    public function element()
    {
        return $this->getCollectionManager()->getCollection('structure');
    }

    /**
     * Возвращает коллекцию для работы с шаблонами.
     * @return LayoutCollection
     */
    public function layout()
    {
        return $this->getCollectionManager()->getCollection('layout');
    }

    /**
     * Возвращает API для работы с автогенерируемым меню структуры
     * @return AutoMenu
     */
    public function structureMenu()
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
}
