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
     * Устанавливает текущий элемент структуры
     * @internal
     * @param StructureElement $element
     */
    public function setCurrentElement(StructureElement $element) {
        $this->currentElement = $element;
    }

    /**
     * Возвращает текущий элемент структуры.
     * @throws RuntimeException если текущий элемент не был установлен
     * @return StructureElement
     */
    public function getCurrentElement() {
        if (!$this->currentElement) {
            throw new RuntimeException($this->translate(
                'Current structure element is not detected.'
            ));
        }
        return $this->currentElement;
    }

    /**
     * Проверяет, был ли установлен текущий элемент структуры.
     * @return bool
     */
    public function hasCurrentElement() {
        return !is_null($this->currentElement);
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
 