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
use umi\orm\collection\ICollection;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\ISimpleCollection;
use umi\orm\collection\ISimpleHierarchicCollection;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\orm\object\ICmsObject;

/**
 * Класс для импорта объектов из дампа в память (ObjectManager).
 */
class CmsObjectImporter implements ICmsObjectImporter, ILocalizable, ICollectionManagerAware
{
    use TLocalizable;
    use TCollectionManagerAware;

    public $requiredFields = [
        ICmsObject::FIELD_GUID,
        ICmsObject::FIELD_TYPE
    ];

    /**
     * Загружает объекты в память из дампа.
     * @param array $dump
     * @throws InvalidArgumentException
     * @return self
     */
    public function loadDump(array $dump)
    {
        foreach ($dump as $objectInfo) {
            $this->loadObjectFromDump($objectInfo);
        }
    }

    /**
     * Загружает объект в память из дампа.
     * @param array $objectInfo
     * @throws InvalidArgumentException
     * @return ICmsObject
     */
    public function loadObjectFromDump(array $objectInfo)
    {
        $this->checkRequiredInfo($objectInfo);

        /**
         * @var ICollection|ISimpleHierarchicCollection|ISimpleCollection $collection
         * @var IObjectType $type
         */
        list ($collection, $type) = $this->getCollectionAndType($objectInfo);

        try {
            $object = $collection->get($objectInfo[ICmsObject::FIELD_GUID], ILocalesService::LOCALE_ALL);
        } catch (NonexistentEntityException $e) {
            if ($collection instanceof ISimpleCollection) {
                $object = $collection->add($type->getName());
            } elseif ($collection instanceof ISimpleHierarchicCollection) {
               // $object = $collection->add()
            }

        }

    }

    /**
     * Возвращает коллекцию и тип объекта
     * @param array $objectInfo
     * @return array
     * @throws \umicms\exception\InvalidArgumentException
     */
    private function getCollectionAndType(array $objectInfo)
    {
        $objectTypeInfo = explode(IObjectType::PATH_SEPARATOR, $objectInfo[ICmsObject::FIELD_TYPE], 2);
        if (count($objectTypeInfo) < 2) {
            throw new InvalidArgumentException($this->translate(
                'Cannot load object from dump. Object type path "{path}" is not correct.',
                ['path' => $objectInfo[ICmsObject::FIELD_TYPE]]
            ));
        }

        list($objectCollectionName, $objectTypeName) = $objectInfo[ICmsObject::FIELD_TYPE];

        $collection =  $this->getCollectionManager()->getCollection($objectCollectionName);

        return [
            $collection,
            $objectType = $collection->getMetadata()->getType($objectTypeName)
        ];
    }
    /**
     * Проверяет, присутствуют ли в информации об объекте обязательные поля.
     * @param array $objectInfo
     * @throws InvalidArgumentException если обяхательные поля отсутствуют
     */
    private function checkRequiredInfo(array $objectInfo) {

        $required = [];
        foreach ($this->requiredFields as $fieldName) {
            if (!isset($objectInfo[$fieldName])) {
                $required[] = $fieldName;
            }
        }
        if ($required) {
            throw new InvalidArgumentException($this->translate(
                'Cannot load object from dump. Fields "{fields}" required in "{dump}".',
                ['fields' => implode(',', $required), 'dump' => var_export($objectInfo, true)]
            ));
        }
    }
}
 