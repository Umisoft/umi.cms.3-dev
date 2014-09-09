<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json\orm;
use umi\orm\metadata\field\IField;
use umi\orm\object\property\IProperty;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\IProjectSettingsAware;
use umicms\project\TProjectSettingsAware;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для объекта.
 */
class CmsObjectSerializer extends BaseSerializer implements IProjectSettingsAware
{
    use TProjectSettingsAware;

    /**
     * Сериализует ICmsObject в JSON.
     * @param ICmsObject $object
     * @param array $options опции сериализации - список полей, которые должны быть отображены
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        $this->configure($object);

        $selectedFields = [];
        if (isset($options['fields'])) {
            $selectedFields = $options['fields'];
        }
        if (isset($this->currentOptions['fields'])) {
            $selectedFields = array_merge($selectedFields, $this->currentOptions['fields']);
        }

        $usedProperties = $this->getUsedProperties($object, $selectedFields);

        $properties = [];
        foreach ($usedProperties as $property) {
            $name = $property->getName();
            if (in_array($name, $this->currentExcludes)) {
                continue;
            }
            $properties[$name] = $object->getValue($name);
        }

        if ($object instanceof ICmsPage) {
            $properties['meta'] = [
                'pageUrl' => $object->getPageUrl(),
                'header' => $object->getHeader()
            ];
            if ($this->getSiteDefaultPageGuid() === $object->guid) {
                $properties['meta']['isDefault'] = true;
            }
        }
        $this->buildProperties($object, $properties);

        $options['fields'] = [ICmsObject::FIELD_DISPLAY_NAME => null];
        $this->delegate($properties, $options);
    }

    /**
     * Позволяет достроить массив с информацией об объекте для сериализации.
     * @param ICmsObject $object
     * @param array $properties
     */
    protected function buildProperties(ICmsObject $object, array &$properties)
    {

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
            if ($object->hasProperty($fieldName)) {
                $properties[$fieldName] = $object->getProperty($fieldName);
            }
        }

        return $properties;
    }

}
 