<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection;

use umi\orm\collection\SimpleHierarchicCollection;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umi\orm\object\property\calculable\ICalculableProperty;
use umicms\exception\RuntimeException;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;

/**
 * {@inheritdoc}
 */
class CmsHierarchicCollection extends SimpleHierarchicCollection implements ICmsCollection
{
    use TCmsCollection;

    /**
     * {@inheritdoc}
     */
    public function add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null)
    {
        if ($branch) {
            /**
             * @var ICalculableProperty $siteChildCount
             * @var ICalculableProperty $adminChildCount
             */
            $siteChildCount = $branch->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT);
            $adminChildCount = $branch->getProperty(CmsHierarchicObject::FIELD_ADMIN_CHILD_COUNT);

            $siteChildCount->recalculate();
            $adminChildCount->recalculate();
        }

        return parent::add($slug, $typeName, $branch, $guid);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(IObject $object)
    {
        /**
         * @var CmsHierarchicObject $object
         */
        if ($parent = $object->getParent()) {
            /**
             * @var ICalculableProperty $siteChildCount
             * @var ICalculableProperty $adminChildCount
             */
            $siteChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_SITE_CHILD_COUNT);
            $adminChildCount = $parent->getProperty(CmsHierarchicObject::FIELD_ADMIN_CHILD_COUNT);

            $siteChildCount->recalculate();
            $adminChildCount->recalculate();
        }

        return parent::delete($object);
    }

    /**
     * Разрешено ли использование slug.
     * @param CmsHierarchicObject|ICmsObject $object объект, слаг которого необходимо проверить
     * @throws RuntimeException в случае если пришел неверный объект или коллекция объекта не совпадает с коллекцией, в которой проверяется slug
     * @return bool
     */
    public function isAllowedSlug(ICmsObject $object)
    {
        if (!$object instanceof CmsHierarchicObject) {
            throw new RuntimeException($this->translate(
                'Cannot check slug. Object should be instance of "{class}".',
                [
                    'class' => CmsHierarchicObject::className()
                ]
            ));
        }

        if (!$this->contains($object)) {
            throw new RuntimeException($this->translate(
                'Object from collection "{objectCollection}" does not belong to "{collection}".',
                [
                    'objectCollection' => $object->getCollectionName(),
                    'collection' => $this->getName()
                ]
            ));
        }

        if ($object->getIsNew() && $this->hasSlug($object)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Проверяет используется ли slug, учитывая родителя объекта.
     * @param CmsHierarchicObject $object объект, для которого необходимо проверить уникальность slug'а
     * @return bool
     */
    protected function hasSlug(CmsHierarchicObject $object)
    {
        $select = $this->select()
            ->fields([CmsHierarchicObject::FIELD_IDENTIFY])
            ->where(CmsHierarchicObject::FIELD_SLUG)
                ->equals($object->getProperty(CmsHierarchicObject::FIELD_SLUG)->getValue())
            ->where(CmsHierarchicObject::FIELD_PARENT)
                ->equals($object->getParent());

        return (bool) $select->getTotal();
    }
}
