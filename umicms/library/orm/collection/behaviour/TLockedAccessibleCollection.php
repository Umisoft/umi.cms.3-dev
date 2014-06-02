<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 