<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\serialization\exception\UnexpectedValueException;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializer;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;
use XMLWriter;

/**
 * Базовый сериализатор объектов в XML
 */
abstract class BaseSerializer implements ISerializer, ISerializationAware, ILocalizable
{
    /**
     * Заголовок xml.
     */
    const XML_HEADER = '<?xml version="1.0" encoding="utf-8"?>';

    use TSerializationAware;
    use TLocalizable;

    /**
     * @var XMLWriter $xmlWriter
     */
    private static $xmlWriter;

    /**
     * {@inheritdoc}
     */
    public function init() {
        $this->getXmlWriter()->openMemory();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function output() {
        return self::XML_HEADER . PHP_EOL . $this->getXmlWriter()->outputMemory(true);
    }

    /**
     * Возвращает XMLWriter.
     * @return XMLWriter
     */
    protected function getXmlWriter() {
        if (!self::$xmlWriter) {
            self::$xmlWriter = new XMLWriter();
            self::$xmlWriter->openMemory();
        }
        return self::$xmlWriter;
    }

    /**
     * Создает аттрибут заданого имени с заданным значением.
     * @param string $name имя аттрибута
     * @param mixed $value значение аттрибута
     * @throws UnexpectedValueException если значение атрибута не является скалярным типом
     */
    protected function writeAttribute($name, $value) {
        if (!is_scalar($value) && !is_null($value)) {
            throw new UnexpectedValueException($this->translate(
                'Cannot create attribute "{name}". Value type "{type}" is not scalar.',
                ['name' => $name, 'type' => gettype($value)]
            ));
        }

        $this->getXmlWriter()->startAttribute($name);
        $this->delegate($value);
        $this->getXmlWriter()->endAttribute();
    }

    /**
     * Создает элемент.
     * @param string $name имя элемента
     * @param array $attributes список атрибутов элемента
     * @param mixed $value значение элемента
     */
    protected function writeElement($name, array $attributes = [], $value = null) {
        $this->getXmlWriter()->startElement($name);

        foreach ($attributes as $name => $attrValue) {
            $this->writeAttribute($name, $attrValue);
        }

        if (!is_null($value)) {
            $this->delegate($value);
        }

        $this->getXmlWriter()->endElement();
    }

    /**
     * Запускает вложенную сериализацию
     * @param $object
     */
    protected function delegate($object) {
        $serializer = $this->getSerializer(ISerializerFactory::TYPE_XML, $object);
        $serializer($object);
    }

}
