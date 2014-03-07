<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;
use umicms\exception\RuntimeException;
use umicms\project\module\structure\object\Layout;
use umicms\project\module\structure\object\StructureElement;

/**
 * API для работы со структурой.
 */
class StructureApi extends BaseComplexApi implements IPublicApi
{
    /**
     * @var StructureElement $currentElement
     */
    protected $currentElement;

    /**
     * Возвращает API для работы с элементами структуры.
     * @return ElementApi
     */
    public function element()
    {
        return $this->getApi('umicms\project\module\structure\api\ElementApi');
    }

    /**
     * Возвращает API для работы с шаблонами.
     * @return LayoutApi
     */
    public function layout()
    {
        return $this->getApi('umicms\project\module\structure\api\LayoutApi');
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
 