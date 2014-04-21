<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\exception\NotAllowedOperationException;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт для коллекций, поддерживающих удаление объектов в корзину.
 */
trait TRecyclableCollection
{
    /**
     * @see TCmsCollection::selectInternal()
     * @return CmsSelector
     */
    abstract protected function selectInternal();
    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see IRecyclableCollection::selectTrashed()
     */
    public function selectTrashed()
    {
        return $this->selectInternal()
            ->where(IRecyclableObject::FIELD_TRASHED)->equals(true);
    }

    /**
     * @see IRecyclableCollection::trash()
     */
    public function trash(IRecyclableObject $object)
    {
        if ($object instanceof ILockedAccessibleObject && $object->locked) {
            throw new NotAllowedOperationException(
                $this->translate(
                    'Cannot trash locked object with GUID "{guid}" from collection "{collection}".',
                    ['guid' => $object->guid, 'collection' => $object->getCollectionName()]
                )
            );
        }

        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(true);

        return $this;
    }

    /**
     * @see IRecyclableCollection::untrash()
     */
    public function untrash(IRecyclableObject $object)
    {
        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);

        return $this;
    }
}
 