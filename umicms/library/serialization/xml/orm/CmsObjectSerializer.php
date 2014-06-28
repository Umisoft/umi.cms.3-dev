<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml\orm;

use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umi\orm\object\property\IProperty;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для CmsObject.
 */
class CmsObjectSerializer extends BaseSerializer
{

    /**
     * Сериализует CmsObject в XML.
     * @param ICmsObject $object
     * @param array $options опции сериализации
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        $this->configure($object);
        /**
         * @var IProperty[] $attributes
         * @var IProperty[] $properties
         */
        $attributes = [];
        $properties = [];

        $selectedFields = [];
        if (isset($options['fields'])) {
            $selectedFields = $options['fields'];
        }
        if (isset($this->currentOptions['fields'])) {
            $selectedFields = array_merge($selectedFields, $this->currentOptions['fields']);
        }
        $usedProperties = $this->getUsedProperties($object, $selectedFields);

        /**
         * @var IProperty $property
         */
        foreach ($usedProperties as $property) {
            $name = $property->getName();

            if (in_array($name, $this->currentExcludes)) {
                continue;
            }

            if (in_array($name, $this->currentAttributes)) {
                $attributes[$name] = $property;
            } else {
                $properties[$name] = $property;
            }
        }

        $this->buildAttributes($object, $attributes);

        $options['fields'] = [ICmsObject::FIELD_DISPLAY_NAME => null];
        foreach ($properties as $name => $property) {
            $this->getXmlWriter()->startElement('property');

           $this->getXmlWriter()->writeAttribute('name', $property->getName());

           $this->delegate($property->getField());

            $this->getXmlWriter()->startElement('value');
            $value = $object->getValue($name);

            if (!is_null($value)) {
                $this->delegate($value, $options);
            }
            $this->getXmlWriter()->endElement();

            $this->getXmlWriter()->endElement();
        }
    }

    /**
     * Сериализует атрибуты
     * @param ICmsObject $object
     * @param array $attributes
     */
    protected function buildAttributes(ICmsObject $object, array $attributes)
    {
        foreach ($attributes as $name => $attribute) {

            $value = $object->getValue($name);
            if ($value instanceof IObjectType) {
                $value = $value->getName();
            }
            if ($value instanceof ICmsObject) {
                $value = $value->getGUID();
            }
            $this->writeAttribute($name, $value);
        }
        if ($object instanceof ICmsPage) {
            $this->writeAttribute('url', $object->getPageUrl());
            $this->writeAttribute('header', $object->getHeader());
        }
    }

    /**
     * Возвращает список свойств объекта для отображения
     * @param ICmsObject $object
     * @param IField[] $fields
     * @return IProperty[]
     */
    protected function getUsedProperties(ICmsObject $object, array $fields = [])
    {
        if (!$fields) {
            return $object->getAllProperties();
        }

        $fields = array_merge($fields, $object->getCollection()->getForcedFieldsToLoad());

        $properties = [];
        foreach($fields as $fieldName => $field) {
            $properties[$fieldName] = $object->getProperty($fieldName);
        }

        return $properties;
    }
}
