<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umi\orm\collection\SimpleHierarchicCollection as FrameworkSimpleHierarchicCollection;
use umi\orm\metadata\field\special\MaterializedPathField;
use umicms\exception\InvalidArgumentException;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\selector\CmsSelector;

/**
 * {@inheritdoc}
 */
class SimpleHierarchicCollection extends FrameworkSimpleHierarchicCollection implements ICmsCollection
{
    use TCmsCollection;

    /**
     * Возвращает селектор для выбора дочерних объектов для указанного.
     * @param CmsHierarchicObject|null $object объект, либо null, если нужна выборка от корня
     * @return CmsSelector
     */
    public function selectChildren(CmsHierarchicObject $object = null)
    {
        return $this->select()
            ->where(CmsHierarchicObject::FIELD_PARENT)->equals($object)
            ->orderBy(CmsHierarchicObject::FIELD_HIERARCHY_LEVEL, CmsSelector::ORDER_ASC)
            ->orderBy(CmsHierarchicObject::FIELD_ORDER);
    }

    /**
     * Возвращает селектор для выбора потомков указанного объекта, либо от корня.
     * @param CmsHierarchicObject|null $object объект, либо null, если нужна выборка от корня
     * @param int|null $depth глубина выбора потомков, по умолчанию выбираются на всю глубину
     * @throws InvalidArgumentException если глубина указана не верно
     * @return CmsSelector|CmsHierarchicObject[]
     */
    public function selectDescendants(CmsHierarchicObject $object = null, $depth = null)
    {
        if (!is_null($depth) && !is_int($depth) && $depth < 0) {
            throw new InvalidArgumentException($this->translate(
                'Cannot select descendants. Invalid argument "depth" value.'
            ));
        }

        if (!is_null($object) && $depth === 1) {
            return $this->selectChildren($object);
        }

        $selector = $this->select();

        if ($object) {
            $selector
                ->where(CmsHierarchicObject::FIELD_MPATH)
                ->like($object->getMaterializedPath() . MaterializedPathField::MPATH_SEPARATOR . '%');
        }

        if ($depth) {
            if ($object) {
                $selector
                    ->where(CmsHierarchicObject::FIELD_HIERARCHY_LEVEL)
                    ->equalsOrLess($object->getLevel() + $depth);
            } else {
                $selector
                    ->where(CmsHierarchicObject::FIELD_HIERARCHY_LEVEL)
                    ->equalsOrLess($depth);
            }
        }

        $selector->orderBy(CmsHierarchicObject::FIELD_ORDER);

        return $selector;
    }

}
