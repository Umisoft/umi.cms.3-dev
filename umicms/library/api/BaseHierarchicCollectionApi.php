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
use umicms\base\object\CmsElement;

/**
 * Базовый класс API для работы с простой иерархической ORM-коллекцией.
 */
abstract class BaseHierarchicCollectionApi extends BaseCollectionApi
{
    /**
     * Возвращает селектор для выбора детей указанного элемента, либо от корня (на один уровень вложенности).
     * @param CmsElement|null $element эелемент, либо null, если нужна выборка от корня
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function selectChildren(CmsElement $element = null, $onlyActive = true)
    {
        return $this
            ->select($onlyActive)
            ->where(CmsElement::FIELD_PARENT)->equals($element)
            ->orderBy(CmsElement::FIELD_ORDER);
    }

    /**
     * Возвращает селектор для выбора родителей элемента.
     * @param CmsElement $element
     * @return ISelector
     */
    public function selectAncestry(CmsElement $element)
    {
        return $this
            ->getCollection()
            ->selectAncestry($element);
    }

    /**
     * Возвращает селектор для выбора потомков указанного элемента, либо от корня.
     * @param CmsElement|null $element эелемент, либо null, если нужна выборка от корня
     * @param int|null $depth глубина выбора потомков, по умолчанию выбираются на всю глубину
     * @param bool $onlyActive учитывать активность
     * @throws InvalidArgumentException
     * @return ISelector
     */
    public function selectDescendants(CmsElement $element = null, $depth = null, $onlyActive = true)
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
                ->where(CmsElement::FIELD_MPATH)
                ->like($element->getMaterializedPath() . MaterializedPathField::MPATH_SEPARATOR . '%');
        }

        if ($depth) {
            $selector
                ->where(CmsElement::FIELD_HIERARCHY_LEVEL)
                ->equalsOrLess($element->getLevel() + $depth);
        }
        $selector->orderBy(CmsElement::FIELD_ORDER);

        return $selector;
    }

    /**
     * Перемещает элемент в ветку после указанного.
     * Если ветка не указана, элемент будет помещен в корень.
     * Если предшественник не указан, элемент будет помещен в начало ветки.
     * @param CmsElement $element перемещаемый элемент
     * @param CmsElement|null $branch ветка, в которую будет перемещен элемент
     * @param CmsElement|null $previousSibling элемент, предшествующий перемещаемому
     * @return self
     */
    public function move(CmsElement $element, CmsElement $branch = null, CmsElement $previousSibling = null)
    {
        $this
            ->getCollection()
            ->move($element, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает простую иерархическую коллекцию.
     * @throws UnexpectedValueException если коллекция не иерархическая
     * @return SimpleHierarchicCollection
     */
    protected function getCollection()
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
     * @return CmsElement
     */
    protected function getElementByUrl($url)
    {
        return $this->getCollection()->getByUri('/' . $url);
    }

}
