<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\view;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\IHierarchicCollection;
use umicms\exception\RuntimeException;
use umicms\hmvc\callstack\IBreadcrumbsStackAware;
use umicms\hmvc\callstack\TBreadcrumbsStackAware;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;
use umicms\hmvc\callstack\IPageCallStackAware;
use umicms\hmvc\callstack\TPageCallStackAware;
use umicms\project\module\structure\model\object\MenuInternalItem;

/**
 * Представление дерева.
 */
class CmsTreeView implements \IteratorAggregate, \Countable, IPageCallStackAware, IBreadcrumbsStackAware, ILocalizable
{
    use TPageCallStackAware;
    use TBreadcrumbsStackAware;
    use TLocalizable;

    /**
     * @var CmsSelector $selector селектор на элементы.
     */
    protected $selector;

    /**
     * Конструктор.
     * @param CmsSelector $selector
     * @throws RuntimeException
     */
    public function __construct(CmsSelector $selector)
    {
        $this->selector = $selector;

        if (!$selector->getCollection() instanceof IHierarchicCollection) {
            throw new RuntimeException($this->translate(
                'Cannot create tree view. Collection is not hierarchical.'
            ));
        }
    }

    /**
     * Возвращает селектор, связанный с View
     * @return CmsSelector
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Возвращает список корневых элементов дерева
     * @return CmsTreeNode[]
     */
    public function getChildren()
    {
        $result = $this->selector->result()->fetchAll();

        $list = [];

        foreach ($result as $resultItem) {
            $parent = $resultItem->getValue('parent');
            $parentId = isset($parent) ? $parent->getId() : null;
            $list[$parentId][$resultItem->getId()] = $resultItem;
        }

        $parentId = $this->getParentId($list);

        return $this->buildTreeNodes($list, $parentId);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getChildren());
    }

    /**
     * Возвращает количество всех элементов дерева.
     * @return int
     */
    public function count()
    {
        return $this->selector->result()->count();
    }

    /**
     * Создает и возвращает дерево.
     * @param array $categories
     * @param int $parentId
     * @return array|null
     */
    private function buildTreeNodes(array $categories, $parentId)
    {
        $nodes = [];
        if (isset($categories[$parentId])) {
            /** @var CmsHierarchicObject|ICmsPage $item */
            foreach($categories[$parentId] as $item) {
                $node = new CmsTreeNode($item, $this->buildTreeNodes($categories, $item->getId()));
                $this->setCurrentAndActive($node);
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    /**
     * Возвращает идентификатор родителя относительно которого строить дерево.
     * @param array $list Массив элементов для построения дерева
     * @return int|null
     */
    private function getParentId(array $list)
    {
        $parentId = null;

        $topItem = reset($list);
        if (is_array($topItem)) {

            $item = reset($topItem);

            if ($item instanceof CmsHierarchicObject && $item->parent instanceof CmsHierarchicObject) {
                $parentId = $item->parent->getId();
            }
        }

        return $parentId;
    }

    /**
     * Определяет значения флагов active и current для ноды дерева.
     * @param CmsTreeNode $node
     */
    private function setCurrentAndActive(CmsTreeNode $node)
    {
        $item = $node->item;
        if ($item instanceof ICmsPage) {
            $node->current = $this->isCurrent($item);
            $node->active = $this->isPageInBreadcrumbs($item);
        } elseif ($item instanceof MenuInternalItem) {
            /** @var MenuInternalItem $item */
            $node->current = $this->isCurrent($item->pageRelation);
            $node->active = $this->isPageInBreadcrumbs($item->pageRelation);
        } else {
            $node->current = false;
            $node->active = false;
        }
    }
}
 