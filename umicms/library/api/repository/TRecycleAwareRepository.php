<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\repository;

use umi\orm\collection\ICommonHierarchy;
use umi\orm\collection\IHierarchicCollection;
use umi\orm\collection\ILinkedHierarchicCollection;
use umi\orm\collection\ISimpleCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт для подключения функционала добавления объектов в корзину в репозиторий.
 */
trait TRecycleAwareRepository
{
    /**
     * Возвращает коллекцию.
     * @internal
     * @return ICommonHierarchy|IHierarchicCollection|ILinkedHierarchicCollection|ISimpleCollection|ICmsCollection
     */
    abstract public function getCollection();

    /**
     * Возвращает селектор для выбора объектов репозитория, помещеных в корзину.
     * @return CmsSelector
     */
    public function selectTrashed()
    {
        return $this->getCollection()->select()
            ->where(IRecyclableObject::FIELD_TRASHED)->equals(true);
    }

    /**
     * Помещает объект в корзину.
     * @param IRecyclableObject $object
     * @return $this
     */
    public function trash(IRecyclableObject $object)
    {
        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(true);

        return $this;
    }

    /**
     * Извлекает объект из корзины.
     * @param IRecyclableObject $object
     * @return $this
     */
    public function untrash(IRecyclableObject $object)
    {
        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);

        return $this;
    }

}
