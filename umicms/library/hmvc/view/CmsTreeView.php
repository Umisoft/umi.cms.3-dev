<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\view;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\IHierarchicCollection;
use umicms\exception\RuntimeException;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * Содержимое результата работы виджета или контроллера, требующее шаблонизации.
 */
class CmsTreeView implements \IteratorAggregate, \Countable, IPageCallStackAware, ILocalizable
{
    use TPageCallStackAware;
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
     * Создаёт и возвращает дерево.
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
                if ($item instanceof ICmsPage) {
                    $node->current = $this->hasPage($item);
                }
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
}
 