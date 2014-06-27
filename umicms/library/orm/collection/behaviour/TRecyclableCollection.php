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

use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
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
        if (!$object->trashed) {
            return $this;
        }

        if ($object instanceof ILockedAccessibleObject && $object->locked) {
            throw new NotAllowedOperationException(
                $this->translate(
                    'Cannot trash locked object with GUID "{guid}" from collection "{collection}".',
                    ['guid' => $object->guid, 'collection' => $object->getCollectionName()]
                )
            );
        }

        if ($object instanceof CmsHierarchicObject && $this instanceof CmsHierarchicCollection) {
            $descendants = $this->selectDescendants($object);
            foreach($descendants as $descendant) {
                $descendant->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(true);
            }
        }

        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(true);

        return $this;
    }

    /**
     * @see IRecyclableCollection::untrash()
     */
    public function untrash(IRecyclableObject $object)
    {
        if ($object->trashed) {
            return $this;
        }

        if ($object instanceof CmsHierarchicObject && $this instanceof CmsHierarchicCollection) {
            $ancestry = $this->selectAncestry($object);
            /**
             * @var CmsHierarchicObject $parent
             */
            foreach($ancestry as $parent) {
                $parent->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);
            }
        }

        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);

        return $this;
    }
}
 