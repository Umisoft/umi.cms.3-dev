<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;
use umi\orm\metadata\field\IField;
use umi\orm\object\property\IProperty;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для объекта.
 */
class CmsObjectSerializer extends BaseSerializer implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Сериализует ICmsObject в JSON.
     * @param ICmsObject $object
     * @param array $options опции сериализации - список полей, которые должны быть отображены
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        $selectedFields = isset($options['fields']) ? $options['fields'] : [];
        $usedProperties = $this->getUsedProperties($object, $selectedFields);

        $properties = [];
        foreach ($usedProperties as $property) {
            $name = $property->getName();
            $properties[$name] = $object->getValue($name);
        }

        if ($object instanceof ICmsPage) {
            $properties['meta'] = ['pageUrl' => $object->getPageUrl()];
        }
        $options['fields'] = array_merge($selectedFields, [ICmsObject::FIELD_DISPLAY_NAME => null]);
        $this->delegate($properties, $options);
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
 