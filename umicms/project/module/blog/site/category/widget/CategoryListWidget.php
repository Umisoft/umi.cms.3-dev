<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\category\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogCategory;

/**
 * Виджет для вывода списка категорий блога.
 */
class CategoryListWidget extends BaseListWidget

{
    /**
     * @var string|null|BlogCategory $parentCategory категория блога или GUID, из которой выводятся дочерние категории.
     * Если не указан, выводятся все корневые категории.
     */
    public $parentCategory;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;

    /**
     * Конструктор.
     * @param BlogModule $module модуль "Блоги"
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        if (is_string($this->parentCategory)) {
            $this->parentCategory = $this->module->category()->get($this->parentCategory);
        }

        if (isset($this->parentCategory) && !$this->parentCategory instanceof BlogCategory) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'parentCategory',
                        'class' => BlogCategory::className()
                    ]
                )
            );
        }

        return  $this->module->getCategories($this->parentCategory);
    }
}
 