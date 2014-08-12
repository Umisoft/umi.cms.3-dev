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

use umi\orm\object\property\calculable\ICalculableProperty;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт для коллекций, поддерживающих удаление объектов в корзину.
 */
trait TRecyclableCollection
{
    /**
     * @see ICmsCollection::getInternalSelector()
     * @return CmsSelector|ICmsObject[]
     */
    abstract public function getInternalSelector();

    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see IRecyclableCollection::selectTrashed()
     */
    public function selectTrashed()
    {
        return $this->getInternalSelector()
            ->where(IRecyclableObject::FIELD_TRASHED)->equals(true);
    }

    /**
     * @see IRecyclableCollection::trash()
     */
    public function trash(IRecyclableObject $object)
    {
        if ($object->trashed) {
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

            if ($parent = $object->getParent()) {
                $siteChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT);
                foreach ($siteChildCount->getField()->getLocalizations() as $localeId => $localeInfo) {
                    /**
                     * @var ICalculableProperty $localizedSiteChildCount
                     */
                    $localizedSiteChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT, $localeId);
                    $localizedSiteChildCount->recalculate();
                }
                /**
                 * @var ICalculableProperty $adminChildCount
                 */
                $adminChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_ADMIN_CHILD_COUNT);
                $adminChildCount->recalculate();
            }

            $descendants = $this->selectDescendants($object);
            /**
             * @var CmsHierarchicObject $descendant
             */
            foreach($descendants as $descendant) {

                $siteChildCount = $descendant->getParent()->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT);
                foreach ($siteChildCount->getField()->getLocalizations() as $localeId => $localeInfo) {
                    $localizedSiteChildCount = $descendant->getParent()->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT, $localeId);
                    $localizedSiteChildCount->recalculate();
                }

                $adminChildCount = $descendant->getParent()->getProperty(CmsHierarchicObject::FIELD_ADMIN_CHILD_COUNT);
                $adminChildCount->recalculate();

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
        if (!$object->trashed) {
            return $this;
        }

        if ($object instanceof CmsHierarchicObject && $this instanceof CmsHierarchicCollection) {
            $ancestry = $this->selectAncestry($object);
            /**
             * @var CmsHierarchicObject $parent
             */
            foreach($ancestry as $parent) {

                $siteChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT);
                foreach ($siteChildCount->getField()->getLocalizations() as $localeId => $localeInfo) {
                    /**
                     * @var ICalculableProperty $localizedSiteChildCount
                     */
                    $localizedSiteChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT, $localeId);
                    $localizedSiteChildCount->recalculate();
                }
                /**
                 * @var ICalculableProperty $adminChildCount
                 */
                $adminChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_ADMIN_CHILD_COUNT);
                $adminChildCount->recalculate();

                $parent->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);
            }
        }

        $object->getProperty(IRecyclableObject::FIELD_TRASHED)->setValue(false);

        return $this;
    }
}
 