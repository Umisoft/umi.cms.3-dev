<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\widget;

use umi\orm\collection\ICollection;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\selector\CmsSelector;

/**
 * Базовый класс виджета вывода деревьев.
 * Применяет условия выборки для дерева.
 */
abstract class BaseTreeWidget extends BaseSecureWidget
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

        if (!$collection instanceof SimpleHierarchicCollection) {
            throw new RuntimeException($this->translate(
                'Cannot create tree. Collection is not hierarchical'
            ));
        }

        $this->checkParentNode($collection);

        $result = $selector;

        if ($this->parentNode instanceof CmsHierarchicObject) {
            $result = $collection->selectDescendants($this->parentNode);

            if ($this->depth) {
                $result = $collection->selectDescendants($this->parentNode, $this->depth);
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
     * Проверяет родительскую ноду. Если был указан GUID получает объект.
     * @param ICollection $collection коллекция для получения родительской ноды
     * @throws InvalidArgumentException в случае если родительская нода не иерархический объект
     */
    private function checkParentNode($collection)
    {
        if (is_string($this->parentNode)) {
            $this->parentNode = $collection->get($this->parentNode);
        }

        if (isset($this->parentNode) && !$this->parentNode instanceof CmsHierarchicObject) {
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
    }
}
 