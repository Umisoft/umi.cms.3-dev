<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;
use umi\orm\collection\ICollection;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\orm\object\property\IProperty;
use umicms\orm\collection\IApplicationHandlersAware;
use umicms\orm\object\ICmsObject;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для объекта.
 */
class CmsObjectSerializer extends BaseSerializer
{
    /**
     * Сериализует ICmsObject в JSON.
     * @param ICmsObject $object
     * @param array $options опции сериализации - список полей, которые должны быть отображены
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        $properties = [];
        $links = [];

        $usedProperties = $this->getUsedProperties($object, $options);

        /**
         * @var IProperty $property
         */
        foreach ($usedProperties as $property) {
            $name = $property->getName();
            /**
             * @var mixed $field
             */
            $field = $property->getField();
            switch(true) {
                case $field instanceof BelongsToRelationField: {

                    $value = $property->getDbValue();
                    if ($value) {
                        /**
                         * @var ICollection|IApplicationHandlersAware $targetCollection
                         */
                        $targetCollection = $field->getTargetCollection();
                        if ($targetCollection instanceof IApplicationHandlersAware && $targetCollection->hasHandler('admin')) {
                            $links[$name] = $this->getCollectionLink($targetCollection, $targetCollection->getIdentifyField()->getName(), $value);
                        }
                    }
                    break;
                }
                case $field instanceof HasManyRelationField: {
                    $targetCollection = $field->getTargetCollection();
                    if ($targetCollection instanceof IApplicationHandlersAware && $targetCollection->hasHandler('admin')) {
                        $links[$name] = $this->getCollectionLink($targetCollection, $field->getTargetFieldName(), $object->getId());
                    }
                    break;
                }
                case $field instanceof ManyToManyRelationField: {
                    $targetCollection = $field->getTargetCollection();
                    $mirrorFieldName = $targetCollection->getMetadata()->getFieldByRelation($field->getTargetFieldName(), $field->getBridgeCollectionName())->getName();

                    if ($targetCollection instanceof IApplicationHandlersAware && $targetCollection->hasHandler('admin')) {
                        $links[$name] = $this->getCollectionLink($targetCollection, $mirrorFieldName, $object->getId());
                    }

                    break;
                }
                default: {
                    $properties[$name] = $object->getValue($name);
                }
            }

        }

        if ($links) {
            $properties['links'] = $links;
        }

        $this->delegate($properties);
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

    //TODO переделать
    protected function getCollectionLink(IApplicationHandlersAware $collection, $filterName, $filterValue)
    {
        $link = '/admin/api/' . str_replace('.', '/', $collection->getHandlerPath('admin'));
        $link .= '/' . $collection->getName() . '?' . http_build_query([$filterName => $filterValue]);

        return $link;
    }
}
 