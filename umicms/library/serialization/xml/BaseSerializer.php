<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\serialization\exception\UnexpectedValueException;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializer;
use umicms\serialization\ISerializerConfigurator;
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
     * @var array $currentAttributes список имен атрибутов
     */
    protected $currentAttributes = [];
    /**
     * @var array $currentExcludes список имен исключений
     */
    protected $currentExcludes = [];
    /**
     * @var array $currentOptions список опций сериализации
     */
    protected $currentOptions = [];

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
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->currentOptions = array_merge($this->currentOptions, $options);

        return $this;
    }

    /**
     * Устанавливает список имен атрибутов.
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->currentAttributes = array_merge($this->currentAttributes, $attributes);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setExcludes(array $excludes)
    {
        $this->currentExcludes = array_merge($this->currentExcludes, $excludes);

        return $this;
    }

    /**
     * Конфигурируется через сериализуемый объект.
     * @param ISerializerConfigurator $configurator
     */
    protected function configure(ISerializerConfigurator $configurator)
    {
        $this->currentExcludes = [];
        $this->currentAttributes = [];
        $this->currentOptions = [];
        $configurator->configureSerializer($this);
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
     * @param array $options опции сериализации
     */
    protected function writeElement($name, array $attributes = [], $value = null, array $options = []) {
        $this->getXmlWriter()->startElement($name);

        foreach ($attributes as $name => $attrValue) {
            $this->writeAttribute($name, $attrValue);
        }

        if (!is_null($value)) {
            $this->delegate($value, $options);
        }

        $this->getXmlWriter()->endElement();
    }

    /**
     * Запускает вложенную сериализацию
     * @param mixed $object
     * @param array $options опции сериализации
     */
    protected function delegate($object, array $options = []) {
        $serializer = $this->getSerializer(ISerializerFactory::TYPE_XML, $object);
        $serializer($object, $options);
    }

}
