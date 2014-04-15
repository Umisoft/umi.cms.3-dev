<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт для коллекций, поддерживающих управлению активностью объекта на сайте.
 */
trait TActiveAccessibleCollection
{
    /**
     * @see TCmsCollection::selectInternal()
     * @return CmsSelector
     */
    abstract protected function selectInternal();

    /**
     * @see IActiveAccessibleCollection::selectActive()
     */
    public function selectActive()
    {
        return $this->selectInternal()
            ->where(IActiveAccessibleObject::FIELD_ACTIVE)->equals(true);
    }

    /**
     * @see IActiveAccessibleCollection::selectInactive()
     */
    public function selectInactive()
    {
        return $this->selectInternal()
            ->where(IActiveAccessibleObject::FIELD_ACTIVE)->equals(false);
    }

    /**
     * @see IActiveAccessibleCollection::activate()
     */
    public function activate(IActiveAccessibleObject $object)
    {
        $object->getProperty(IActiveAccessibleObject::FIELD_ACTIVE)->setValue(true);

        return $this;
    }

    /**
     * @see IActiveAccessibleCollection::deactivate()
     */
    public function deactivate(IActiveAccessibleObject $object)
    {
        $object->getProperty(IActiveAccessibleObject::FIELD_ACTIVE)->setValue(false);

        return $this;
    }
}
 