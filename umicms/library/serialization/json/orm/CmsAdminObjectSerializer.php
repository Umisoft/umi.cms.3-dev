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
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\orm\metadata\field\special\MaterializedPathField;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;

/**
 * JSON-сериализатор для объекта.
 */
class CmsAdminObjectSerializer extends CmsObjectSerializer implements IUrlManagerAware
{
    use TUrlManagerAware;

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

        foreach ($usedProperties as $property) {
            $name = $property->getName();

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
                                $links[$name] = $this->getTargetCollectionResourceUri($targetCollection, $targetCollection->getIdentifyField()->getName(), $value);
                            }
                        }
                    }
                    break;
                }
                case $field instanceof HasManyRelationField: {
                    $targetCollection = $field->getTargetCollection();
                    if ($targetCollection->hasHandler('admin')) {
                        $links[$name] = $this->getTargetCollectionResourceUri($targetCollection, $field->getTargetFieldName(), $object->getId());
                    }
                    break;
                }
                case $field instanceof ManyToManyRelationField: {
                    /**
                     * @var ICmsCollection $bridgeCollection
                     */
                    $bridgeCollection = $field->getBridgeCollection();
                    if ($bridgeCollection->hasHandler('admin')) {
                        $links[$name] = $this->getBridgeCollectionResourceUri(
                            $bridgeCollection,
                            $field->getRelatedFieldName(),
                            $object->getId(),
                            $field->getTargetFieldName()
                        );
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

        $meta = [];

        try {
            $meta['editLink'] = $object->getEditLink();
        } catch (\Exception $e) { }

        if ($object instanceof ICmsPage) {
            $meta['pageUrl'] = $object->getPageUrl();
        }

        if ($meta) {
            $properties['meta'] = $meta;
        }

        $this->delegate($properties, $options);
    }

    /**
     * Возвращает ссылку на REST-ресурс для получения значений связи объекта через target-коллекцию
     * @param ICmsCollection $collection связанная коллекция
     * @param string $filterName имя связанного поля
     * @param string $filterValue значение связанного поля
     * @return string
     */
    protected function getTargetCollectionResourceUri(ICmsCollection $collection, $filterName, $filterValue)
    {
        $link = $this->getUrlManager()->getCollectionResourceUrl($collection);
        $link .= '?' . http_build_query(['filters' => [$filterName => $filterValue]]);

        return $link;
    }

    /**
     * Возвращает ссылку на REST-ресурс для получения значений связи объекта через bridge-коллекцию
     * @param ICmsCollection $collection bridge-коллекция
     * @param string $filterName имя поля, через которое осуществляется связь с коллекцией объекта
     * @param string $filterValue идентификатор объекта
     * @param string $withName имя поля, через которое осуществляется связь с target-коллекцией
     * @return string
     */
    protected function getBridgeCollectionResourceUri(ICmsCollection $collection, $filterName, $filterValue, $withName)
    {
        $link = $this->getUrlManager()->getCollectionResourceUrl($collection);
        $link .= '?' . http_build_query(
            [
                'filters' => [$filterName => $filterValue],
                'with' => [$withName => ICmsObject::FIELD_DISPLAY_NAME]
            ]
        );

        return $link;
    }
}
 