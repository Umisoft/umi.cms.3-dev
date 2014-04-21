<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\orm\object\behaviour\ILockedAccessibleObject;

/**
 * Трейт для коллекций, поддерживающих управлению заблокированностью объекта.
 */
trait TLockedAccessibleCollection
{
    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see ILockedAccessibleCollection::lock()
     */
    public function lock(ILockedAccessibleObject $object)
    {
        $object->getProperty(ILockedAccessibleObject::FIELD_LOCKED)->setValue(true);

        return $this;
    }

    /**
     * @see ILockedAccessibleCollection::unlock()
     */
    public function unlock(ILockedAccessibleObject $object)
    {
        $object->getProperty(ILockedAccessibleObject::FIELD_LOCKED)->setValue(false);

        return $this;
    }

}
 