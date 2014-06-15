<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\orm\collection\ICollection;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\selector\CmsSelector;

/**
 * Базовый класс виджета вывода деревьев.
 * Применяет условия выборки для дерева.
 */
abstract class BaseTreeWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'tree';
    /**
     * Если не указано, строится полное дерево
     * @var CmsHierarchicObject $parentNode родительская нода или GUID родительской ноды
     */
    public $parentNode;
    /**
     * Если не указано, строится на всю глубину вложенности
     * @var int $depth глубина вложения
     */
    public $depth;

    /**
     * Возвращает выборку для построения дерева.
     * @return CmsSelector
     */
    abstract protected function getSelector();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $selector = $this->applySelectorConditions($this->getSelector());

        $collection = $selector->getCollection();

        if (!$collection instanceof CmsHierarchicCollection) {
            throw new RuntimeException($this->translate(
                'Cannot create tree. Collection is not hierarchical'
            ));
        }

        $parentNode = $this->getParentNode($collection);

        $result = $selector;

        if ($parentNode instanceof CmsHierarchicObject) {
            $result = $collection->selectDescendants($parentNode);

            if ($this->depth) {
                $result = $collection->selectDescendants($parentNode, $this->depth);
            }
        } else if ($this->depth) {
            $result = $collection->selectDescendants(null, $this->depth);
        }

        return $this->createTreeResult($this->template, $result);
    }

    /**
     * Пременяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {
        //TODO применение фильтров
        return $selector;
    }

    /**
     * Возвращает родительскую ноду. Если был указан GUID получает объект.
     * @param ICollection $collection коллекция для получения родительской ноды
     * @throws InvalidArgumentException в случае если родительская нода не иерархический объект
     * @return CmsHierarchicObject
     */
    private function getParentNode($collection)
    {
        $parentNode = $this->parentNode;

        if (is_string($parentNode)) {
            $parentNode = $collection->get($parentNode);
        }

        if (!is_null($parentNode) && !$parentNode instanceof CmsHierarchicObject) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'parentNode',
                        'class' => 'CmsHierarchicObject'
                    ]
                )
            );
        }

        return $parentNode;
    }
}
 