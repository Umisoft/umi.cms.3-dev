<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\dump;

use umi\orm\metadata\field\IField;
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\relation\ObjectRelationField;
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;

/**
 * Класс для экспорта объектов в дамп.
 */
class CmsObjectExporter implements ICmsObjectExporter
{
    /**
     * @var array $ignoreFieldTypes список игнорируемых в экспорте типов полей
     */
    public $ignoreFieldTypes = [
        IField::TYPE_VERSION => [],
        IField::TYPE_HAS_MANY => [],
        IField::TYPE_HAS_ONE => [],
        IField::TYPE_MANY_TO_MANY => [],
        IField::TYPE_COUNTER => [],
        IField::TYPE_FORMULA => [],
        IField::TYPE_SLUG => [],

        IField::TYPE_IDENTIFY => [],
        IField::TYPE_URI => [],
        IField::TYPE_GUID => [],
        IField::TYPE_ORDER => [],
        IField::TYPE_MPATH => [],
        IField::TYPE_LEVEL => []
    ];

    /**
     * @var array $ignoreFieldNames список игнорируемых в экспорте имен полей
     */
    public $ignoreFieldNames = [
        IObject::FIELD_TYPE => [],
        IHierarchicObject::FIELD_PARENT => []
    ];


    /**
     * Возвращает дамп объектов, выбранных селектором.
     * @param CmsSelector $selector
     * @return array
     */
    public function getDump(CmsSelector $selector)
    {
        $data = [];

        foreach ($selector as $object)
        {
            $data[] = $this->getObjectData($object);
        }

        return $data;
    }

    /**
     * Возвращает дамп объекта.
     * @param ICmsObject $object
     * @param bool $onlyMeta возвращать ли только минимальные мета-данные
     * @return array
     */
    public function getObjectData(ICmsObject $object, $onlyMeta = false)
    {
        $dump = [
            'meta' => $this->getObjectMeta($object)
        ];

        if ($onlyMeta) {
            return $dump;
        }

        $dump['data'] = [];

        foreach ($object->getAllProperties() as $fullName => $property)
        {
            $field = $property->getField();

            if (isset($this->ignoreFieldTypes[$field->getType()])) {
                continue;
            }

            if (isset($this->ignoreFieldNames[$field->getName()])) {
                continue;
            }

            if ($field instanceof ObjectRelationField || $field instanceof BelongsToRelationField) {
                $relatedObject = $property->getPersistedValue();
                if ($relatedObject instanceof ICmsObject) {
                    $dump['data'][$fullName] = [
                        'relation',
                        $this->getObjectData($relatedObject, true)
                    ];
                }
            } else {
                $value = $property->getValue();

                if (is_array($value) || is_object($value)) {
                    $dump['data'][$fullName] = [gettype($value), serialize($value)];
                } elseif (!is_null($value)) {
                    $dump['data'][$fullName] = [gettype($value), $value];
                }
            }
        }

        return $dump;
    }

    /**
     * Возвращает мета-информацию об объекте.
     * @param ICmsObject $object
     * @return array
     */
    protected function getObjectMeta(ICmsObject $object)
    {
        $meta = [
            'collection' => $object->getCollectionName(),
            'type' => $object->getTypeName(),
            'guid' => $object->guid,
            'displayName' => $object->displayName,
        ];

        if ($object instanceof CmsHierarchicObject) {
            $parent = $object->getParent();
            if ($parent instanceof ICmsObject) {
                $meta['branch'] = $this->getObjectData($parent, true);
            } else {
                $meta['branch'] = null;
            }
        }

        if ($object->hasProperty(CmsHierarchicObject::FIELD_SLUG)) {
            $meta['slug'] = $object->getValue(CmsHierarchicObject::FIELD_SLUG);
        }

        return $meta;
    }

}
 