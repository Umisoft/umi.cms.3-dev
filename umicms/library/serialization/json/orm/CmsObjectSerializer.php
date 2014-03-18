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
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\orm\metadata\field\special\MaterializedPathField;
use umi\orm\object\property\IProperty;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
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

        $selectedFields = isset($options['fields']) ? $options['fields'] : [];
        $usedProperties = $this->getUsedProperties($object, $selectedFields);

        /**
         * @var IProperty $property
         */
        foreach ($usedProperties as $property) {
            $name = $property->getName();

            if ($name == ICmsObject::FIELD_TYPE) {
                $properties[$name] = $object->getType()->getName(); //TODO убрать, когда будут формы для админки
                continue;
            }

            if ($name == CmsHierarchicObject::FIELD_MPATH && $object instanceof CmsHierarchicObject) {

                $value = explode(
                    MaterializedPathField::MPATH_SEPARATOR,
                    trim($object->getMaterializedPath(), MaterializedPathField::MPATH_START_SYMBOL)
                );

                $properties[$name] = array_map('intval', $value);
                continue;
            }

            /**
             * @var mixed $field
             */
            $field = $property->getField();
            switch(true) {
                case $field instanceof BelongsToRelationField: {

                    $value = $property->getDbValue();
                    if ($value) {

                        if ($property->getIsValuePrepared()) {
                            $properties[$name] = (int) $value;
                        } else {
                            /**
                             * @var ICmsCollection $targetCollection
                             */
                            $targetCollection = $field->getTargetCollection();
                            if ($targetCollection->hasHandler('admin')) {
                                $links[$name] = $this->getCollectionLink($targetCollection, $targetCollection->getIdentifyField()->getName(), $value);
                            }
                        }
                    }
                    break;
                }
                case $field instanceof HasManyRelationField: {
                    $targetCollection = $field->getTargetCollection();
                    if ($targetCollection->hasHandler('admin')) {
                        $links[$name] = $this->getCollectionLink($targetCollection, $field->getTargetFieldName(), $object->getId());
                    }
                    break;
                }
                case $field instanceof ManyToManyRelationField: {
                    $targetCollection = $field->getTargetCollection();
                    $mirrorFieldName = $targetCollection->getMetadata()->getFieldByRelation($field->getTargetFieldName(), $field->getBridgeCollectionName())->getName();

                    if ($targetCollection->hasHandler('admin')) {
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

        if ($object instanceof ICmsPage) {
            $properties['meta'] = ['pageUrl' => $object->getPageUrl()];
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
    protected function getCollectionLink(ICmsCollection $collection, $filterName, $filterValue)
    {
        $link = '/admin/api/' . str_replace('.', '/', $collection->getHandlerPath('admin'));
        $link .= '/collection/' . $collection->getName() . '?' . http_build_query(['filters' => [$filterName => $filterValue]]);

        return $link;
    }
}
 