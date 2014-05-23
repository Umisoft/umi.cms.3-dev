<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\view;

use umicms\orm\object\CmsHierarchicObject;

/**
 * Класс описывающий ноду дерева.
 *
 * @property CmsTreeNode[] $children список детей элемента
 * @property CmsHierarchicObject $item элемент
 */
class CmsTreeNode extends \ArrayObject
{
    /**
     * Конструктор.
     * @param CmsHierarchicObject $item элемент
     * @param CmsTreeNode[] $children список детей элемента
     */
    public function __construct(CmsHierarchicObject $item, array $children)
    {
        parent::__construct(['item' => $item, 'children' => $children], self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }
}
 