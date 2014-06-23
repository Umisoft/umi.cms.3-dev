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
 