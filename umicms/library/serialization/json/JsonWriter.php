<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use stdClass;
use umicms\serialization\exception\LogicException;

/**
 * Класс для создания JSON.
 */
class JsonWriter
{
    /**
     * @var int $options опции сериализации в JSON
     */
    protected $options;
    /**
     * @var stdClass $rootElement
     */
    protected $rootElement;
    /**
     * @var stdClass $rootElement
     */
    protected $currentElement;

    /**
     * Конструктор.
     * @param int $options опции сериализации в JSON (см. json_encode())
     */
    public function __construct($options = JSON_UNESCAPED_UNICODE)
    {
        $this->options = $options;
        $this->init();
    }

    /**
     * Инициализирует новый JSON-документ.
     */
    public function init()
    {
        $this->rootElement = $this->currentElement = $this->createElement();
    }

    /**
     * Возвращает JSON.
     * @throws LogicException если документ не завершен.
     * @return string
     */
    public function output()
    {
        if ($this->currentElement !== $this->rootElement) {
            throw new LogicException('Cannot write json. Document is incomplete.');
        }

        return json_encode($this->collectArray($this->rootElement), $this->options);
    }

    /**
     * Созает новый элемент и делает его текущим.
     * @param string $name
     * @throws LogicException если не удалось создать элемент
     * @return $this
     */
    public function startElement($name)
    {
        if (is_null($this->currentElement->value)) {
            $this->currentElement->value = [];
        } elseif (!is_array($this->currentElement->value)) {
            throw new LogicException(
                sprintf(
                    'Cannot start child element "%s". Current element "%s" already has a value.',
                    $name,
                    $this->currentElement->name
                )
            );
        } elseif (isset($this->currentElement->value[$name])) {
            throw new LogicException(
                sprintf(
                    'Cannot start element "%s". Element already exists in "%s".',
                    $name,
                    $this->currentElement->name
                )
            );
        }

        $element = $this->createElement($this->currentElement);
        $this->currentElement->value[$name] = $element;
        $this->currentElement = $element;

        return $this;
    }

    /**
     * Завершает текущий элемент.
     * @throws LogicException если не удалось завершить элемент
     * @return $this
     */
    public function endElement()
    {
        if ($this->currentElement === $this->rootElement) {
            throw new LogicException('Cannot end element. Current element is root.');
        }

        $this->currentElement = $this->currentElement->parent;

        return $this;
    }

    /**
     * Записывает значение в текущий элемент.
     * @param mixed $value
     * @throws LogicException если элемент уже имеет значение
     */
    public function write($value)
    {
        if (!is_null($this->currentElement->value)) {
            throw new LogicException(
                sprintf(
                    'Cannot write element value. Element "%s" already has a value.',
                    $this->currentElement->name
                )
            );
        }
        $this->currentElement->value = $value;
    }

    /**
     * Возвращает данные для преобразования в JSON.
     * @param stdClass $element
     * @return mixed
     */
    protected function collectArray(stdClass $element)
    {
        if (is_array($element->value)) {
            $result = [];
            foreach ($element->value as $name => $child) {
                $result[$name] = $this->collectArray($child);
            }
        } else {
            $result = $element->value;
        }

        return $result;
    }

    /**
     * Создает JSON-элемент
     * @param stdClass $parent родительский элемент
     * @return stdClass
     */
    protected function createElement(stdClass $parent = null)
    {
        $element = new stdClass();
        $element->parent = $parent;
        $element->value = null;

        return $element;
    }

}
