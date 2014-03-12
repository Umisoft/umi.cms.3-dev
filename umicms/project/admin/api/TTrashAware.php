<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\admin\api;

use umi\orm\collection\ICommonHierarchy;
use umi\orm\collection\IHierarchicCollection;
use umi\orm\collection\ILinkedHierarchicCollection;
use umi\orm\collection\ISimpleCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ITrashableObject;

/**
 * Трейт, добавляющий способность помещать объекты системы в корзину и восстанавливать их оттуда
 */
trait TTrashAware
{
    /**
     * Возвращает коллекцию.
     * @internal
     * @return ICommonHierarchy|IHierarchicCollection|ILinkedHierarchicCollection|ISimpleCollection
     */
    abstract public function getCollection();

    /**
     * @param ITrashableObject $object
     */
    public function trash(ITrashableObject $object)
    {
        $object->getProperty(ITrashableObject::FIELD_TRASHED)->setValue(true);
    }

    /**
     * @param ITrashableObject $object
     */
    public function untrash(ITrashableObject $object)
    {
        $object->getProperty(ITrashableObject::FIELD_TRASHED)->setValue(false);
    }

    /**
     * Удаляет все объекты коллекции, помещенные в корзину.
     * @return int Количество удлаенных в корзину объектов.
     */
    public function emptyTrash()
    {
        $selector = $this->getCollection()
            ->select()
            ->where(ICmsObject::FIELD_TRASHED)
            ->equals(true);
        foreach ($selector->getResult() as $object) {
            $this->getCollection()->delete($object);
        }
        return $selector->getResult()->count();
    }
}
