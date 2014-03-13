<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\repository;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\exception\RuntimeException;
use umicms\orm\collection\CommonHierarchy;
use umicms\orm\collection\LinkedHierarchicCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\IRecyclableObject;
use umicms\orm\selector\CmsSelector;

/**
 * Базовый класс API-репозитория объектов UMI.CMS
 */
abstract class BaseObjectRepository implements ICollectionManagerAware, ILocalizable
{
    use TCollectionManagerAware;
    use TLocalizable;

    /**
     * @var string $collectionName имя коллекции
     */
    protected $collectionName;

    /**
     * Возвращает коллекцию с которой связан репозиторий.
     * @return SimpleCollection|SimpleHierarchicCollection|CommonHierarchy|LinkedHierarchicCollection
     */
    public function getCollection()
    {
        return $this->getCollectionManager()->getCollection($this->collectionName);
    }

    /**
     * Возвращает селектор для выбора объектов из репозитория.
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @return CmsSelector|CmsObject[]
     */
    protected function selectAll($onlyPublic = true)
    {
        $selector =  $this->getCollection()->select();
        $type = $this->getCollection()->getMetadata()->getBaseType();

        if ($type->getFieldExists(IRecyclableObject::FIELD_TRASHED)) {
            $selector->where(IRecyclableObject::FIELD_TRASHED)->notEquals(true);
        }

        if ($onlyPublic && $type->getFieldExists(ICmsObject::FIELD_ACTIVE)) {
            $selector->where(ICmsObject::FIELD_ACTIVE)->equals(true);
        }

        return $selector;
    }

    /**
     * Выбирает объект по уникальному идентификатору.
     * @param integer|string $objectId
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @param bool $withLocalization загружать ли значения локализованных свойств объекта.
     * По умолчанию выключено.
     * @throws \Exception если не удалось выбрать объект по каким-либо причинам
     * @return CmsObject
     */
    protected function selectById($objectId, $onlyPublic = true, $withLocalization = false)
    {
        /**
         * @var CmsObject $object
         */
        $object = $this->getCollection()->getById($objectId, $withLocalization);

        if ($object instanceof IRecyclableObject && $object->trashed) {
            throw new RuntimeException($this->translate(
                'Cannot select object by id "{id}". Object in trash.',
                ['id' => $objectId]
            ));
        }

        if ($onlyPublic && !$object->active) {
            throw new RuntimeException($this->translate(
                'Cannot select object by id "{id}". Object inactive.',
                ['id' => $objectId]
            ));
        }

        return $object;
    }

    /**
     * Выбирает объект по GUID.
     * @param string $guid
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @param bool $withLocalization загружать ли значения локализованных свойств объекта.
     * По умолчанию выключено.
     * @throws \Exception если не удалось выбрать объект по каким-либо причинам
     * @return CmsObject
     */
    protected function selectByGuid($guid, $onlyPublic = true, $withLocalization = false)
    {
        /**
         * @var CmsObject|IRecyclableObject $object
         */
        $object = $this->getCollection()->get($guid, $withLocalization);

        if ($object instanceof IRecyclableObject && $object->trashed) {
            throw new RuntimeException($this->translate(
                'Cannot select object by guid "{guid}". Object in trash.',
                ['guid' => $guid]
            ));
        }

        if ($onlyPublic && !$object->active) {
            throw new RuntimeException($this->translate(
                'Cannot select object by guid "{guid}". Object inactive.',
                ['guid' => $guid]
            ));
        }

        return $object;
    }

}
