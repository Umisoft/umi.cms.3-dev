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

use umi\i18n\ILocalesService;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\field\IField;
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\CmsCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;

/**
 * Класс для импорта объектов из дампа в память (ObjectManager).
 */
class CmsObjectImporter implements ICmsObjectImporter, ILocalizable, ICollectionManagerAware
{
    use TLocalizable;
    use TCollectionManagerAware;

    /**
     * @var array $ignoreFieldTypes список игнорируемых в импорте типов полей
     */
    public $ignoreFieldTypes = [
        IField::TYPE_VERSION => [],
        IField::TYPE_HAS_MANY => [],
        IField::TYPE_HAS_ONE => [],
        IField::TYPE_MANY_TO_MANY => [],
        IField::TYPE_COUNTER => [],
        IField::TYPE_SLUG => [],

        IField::TYPE_IDENTIFY => [],
        IField::TYPE_URI => [],
        IField::TYPE_GUID => [],
        IField::TYPE_ORDER => [],
        IField::TYPE_MPATH => [],
        IField::TYPE_LEVEL => []
    ];

    /**
     * @var array $ignoreFieldNames список игнорируемых в импорте имен полей
     */
    public $ignoreFieldNames = [
        IObject::FIELD_TYPE => [],
        IHierarchicObject::FIELD_PARENT => []
    ];

    /**
     * Загружает объекты в память из дампа.
     * @param array $dump
     * @throws InvalidArgumentException
     * @return self
     */
    public function loadDump($dump)
    {
        if (!is_array($dump)) {
            throw new InvalidArgumentException('Cannot load dump. Dump should be an array.');
        }

        foreach ($dump as $objectInfo) {
            $this->loadObjectFromDump($objectInfo);
        }
    }

    /**
     * Загружает объект в память из дампа.
     * @param array $objectInfo
     * @throws RuntimeException
     * @return ICmsObject
     */
    public function loadObjectFromDump(array $objectInfo)
    {
        if (!isset(
            $objectInfo['meta']['collection'],
            $objectInfo['meta']['type'],
            $objectInfo['meta']['guid'],
            $objectInfo['meta']['displayName']
        )) {
            throw new RuntimeException('Cannot load object. Meta corrupted.');
        }

        $collection = $this->getCollectionManager()->getCollection($objectInfo['meta']['collection']);
        $typeName = $objectInfo['meta']['type'];
        $displayName = $objectInfo['meta']['displayName'];
        $guid = $objectInfo['meta']['guid'];
        $slug = isset($objectInfo['meta']['slug']) ? $objectInfo['meta']['slug'] : null;
        $branchInfo =  isset($objectInfo['meta']['branch']) ? $objectInfo['meta']['branch'] : null;
        $objectData = isset($objectInfo['data']) ? $objectInfo['data'] : [];

        try {
            $object = $collection->get($guid, ILocalesService::LOCALE_ALL);
        } catch (NonexistentEntityException $e) {
            if ($collection instanceof CmsCollection) {
                /**
                 * @var CmsObject|ICmsPage $object
                 */
                $object = $collection->add($typeName, $guid);
                if ($object instanceof ICmsPage) {
                    $object->slug = $slug;
                }
            } elseif ($collection instanceof CmsHierarchicCollection) {
                $branch = null;
                if (is_array($branchInfo)) {
                    $branch = $this->loadObjectFromDump($branchInfo);
                    if (!$branch instanceof CmsHierarchicObject) {
                        throw new RuntimeException($this->translate(
                            'Cannot create object {displayName}. Object branch is not hierarchical.',
                            ['displayName' => $displayName]
                        ));
                    }
                }

                $object = $collection->add($slug, $typeName, $branch, $guid);
            } else {
                throw new RuntimeException($this->translate(
                    'Cannot create object {displayName}. Unsupported collection type.',
                    ['displayName' => $displayName]
                ));
            }
            $object->displayName = $displayName;
        }

        if ($objectData) {
            $this->loadObjectProperties($object, $objectData);
        }

        return $object;
    }

    /**
     * Загружает свойства в объект.
     * @param ICmsObject $object
     * @param array $data
     * @throws RuntimeException
     */
    protected function loadObjectProperties(ICmsObject $object, array $data)
    {
        foreach ($data as $fullName => $valueInfo)
        {
            list ($propName, $localeId) = $object->splitFullPropName($fullName);
            $property = $object->getProperty($propName, $localeId);

            $field = $property->getField();

            if (isset($this->ignoreFieldTypes[$field->getType()])) {
                continue;
            }

            if (isset($this->ignoreFieldNames[$field->getName()])) {
                continue;
            }

            $property->setValue($this->unpackValue($valueInfo));
        }
    }

    /**
     * Распаковывает значение свойства
     * @param array $valueInfo
     * @throws RuntimeException
     * @return mixed
     */
    private function unpackValue($valueInfo)
    {
        if (!is_array($valueInfo) || count($valueInfo) != 2) {
            throw new RuntimeException('Cannot load property for object');
        }
        list ($valueType, $dumpValue) = $valueInfo;

        switch ($valueType)
        {
            case 'object':
            case 'array':
                return unserialize($dumpValue);
            case 'relation':
                return $this->loadObjectFromDump($dumpValue);
        }

        return $dumpValue;
    }
}
 