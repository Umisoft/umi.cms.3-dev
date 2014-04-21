<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml\object;

use umi\orm\object\property\IProperty;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\ICmsObject;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для CmsObject.
 */
class CmsObjectSerializer extends BaseSerializer
{
    /**
     * Список полей, которые будут выведены как атрибуты элемента.
     * @var array $attributes
     */
    protected $attributes = [
        ICmsObject::FIELD_IDENTIFY,
        ICmsObject::FIELD_GUID,
        ICmsObject::FIELD_VERSION,
        ICmsObject::FIELD_DISPLAY_NAME,
        IActiveAccessibleObject::FIELD_ACTIVE
    ];

    /**
     * Сериализует CmsObject в XML.
     * @param ICmsObject $object
     * @param array $options опции сериализации
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        /**
         * @var IProperty[] $attributes
         * @var IProperty[] $properties
         */
        $attributes = [];
        $properties = [];

        /**
         * @var IProperty $property
         */
        foreach ($object->getLoadedProperties() as $property) {
            $name = $property->getName();
            if (in_array($name, $this->attributes)) {
                $attributes[$name] = $property;
            } else {
                $properties[$name] = $property;
            }
        }

        foreach ($attributes as $name => $attribute) {
            $this->writeAttribute($name, $object->getValue($name));
        }

        foreach ($properties as $name => $property) {
            $this->getXmlWriter()->startElement('property');
            $this->getXmlWriter()->writeAttribute('name', $property->getName());

            $this->delegate($property->getField());

            $this->writeElement('value', [], $object->getValue($name));

            $this->getXmlWriter()->endElement();
        }


    }
}
