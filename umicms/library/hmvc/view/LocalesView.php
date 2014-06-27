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
 * Класс, описывающий список локалей.
 *
 * @property CmsTreeNode[] $children список детей элемента
 * @property CmsHierarchicObject $item элемент
 */
class LocalesView extends \ArrayObject
{
    /**
     * @var array $locales список локалей
     */
    protected $locales = [];
    /**
     * Конструктор.
     * @param array $locales список локалей
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
        parent::__construct([$locales], self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->locales);
    }
}
 