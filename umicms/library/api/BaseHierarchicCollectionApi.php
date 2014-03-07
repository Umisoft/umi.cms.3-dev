<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api;

use umi\orm\collection\SimpleHierarchicCollection;
use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\field\special\MaterializedPathField;
use umi\orm\selector\ISelector;
use umicms\exception\InvalidArgumentException;
use umicms\exception\UnexpectedValueException;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Базовый класс API для работы с простой иерархической ORM-коллекцией.
 */
abstract class BaseHierarchicCollectionApi extends BaseCollectionApi
{
    /**
     * Возвращает селектор для выбора детей указанного элемента, либо от корня (на один уровень вложенности).
     * @param CmsHierarchicObject|null $element эелемент, либо null, если нужна выборка от корня
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function selectChildren(CmsHierarchicObject $element = null, $onlyActive = true)
    {
        return $this
            ->select($onlyActive)
            ->where(CmsHierarchicObject::FIELD_PARENT)->equals($element)
            ->orderBy(CmsHierarchicObject::FIELD_ORDER);
    }

    /**
     * Возвращает селектор для выбора родителей элемента.
     * @param CmsHierarchicObject $element
     * @return ISelector
     */
    public function selectAncestry(CmsHierarchicObject $element)
    {
        return $this
            ->getCollection()
            ->selectAncestry($element);
    }

    /**
     * Возвращает селектор для выбора потомков указанного элемента, либо от корня.
     * @param CmsHierarchicObject|null $element эелемент, либо null, если нужна выборка от корня
     * @param int|null $depth глубина выбора потомков, по умолчанию выбираются на всю глубину
     * @param bool $onlyActive учитывать активность
     * @throws InvalidArgumentException
     * @return ISelector
     */
    public function selectDescendants(CmsHierarchicObject $element = null, $depth = null, $onlyActive = true)
    {
        if (!is_null($depth) && !is_int($depth) && $depth < 0) {
            throw new InvalidArgumentException($this->translate(
                'Cannot select descendants. Invalid argument "depth" value.'
            ));
        }

        if ($depth == 1) {
            return $this->selectChildren($element, $onlyActive);
        }

        $selector = $this->select($onlyActive);

        if ($element) {
            $selector
                ->where(CmsHierarchicObject::FIELD_MPATH)
                ->like($element->getMaterializedPath() . MaterializedPathField::MPATH_SEPARATOR . '%');
        }

        if ($depth) {
            $selector
                ->where(CmsHierarchicObject::FIELD_HIERARCHY_LEVEL)
                ->equalsOrLess($element->getLevel() + $depth);
        }
        $selector->orderBy(CmsHierarchicObject::FIELD_ORDER);

        return $selector;
    }

    /**
     * Перемещает элемент в ветку после указанного.
     * Если ветка не указана, элемент будет помещен в корень.
     * Если предшественник не указан, элемент будет помещен в начало ветки.
     * @param CmsHierarchicObject $element перемещаемый элемент
     * @param CmsHierarchicObject|null $branch ветка, в которую будет перемещен элемент
     * @param CmsHierarchicObject|null $previousSibling элемент, предшествующий перемещаемому
     * @return self
     */
    public function move(CmsHierarchicObject $element, CmsHierarchicObject $branch = null, CmsHierarchicObject $previousSibling = null)
    {
        $this
            ->getCollection()
            ->move($element, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает простую иерархическую коллекцию.
     * @internal
     * @throws UnexpectedValueException если коллекция не иерархическая
     * @return SimpleHierarchicCollection
     */
    public function getCollection()
    {
        $collection = $this->getCollectionManager()->getCollection($this->collectionName);
        if (!$collection instanceof SimpleHierarchicCollection) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Collection "{name}" should be hierarchical.',
                    ['name' => $this->collectionName]
                )
            );
        }

        return $collection;
    }

    /**
     * Возвращает элемент по его URL
     * @param string $url URL элемента без начального слеша
     * @throws NonexistentEntityException если элемент не найден
     * @return CmsHierarchicObject
     */
    protected function getElementByUrl($url)
    {
        return $this->getCollection()->getByUri('/' . $url);
    }

}
