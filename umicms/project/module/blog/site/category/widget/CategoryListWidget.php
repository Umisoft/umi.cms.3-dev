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
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogCategory;

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
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $blogModule API модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        if (is_string($this->parentCategory)) {
            $this->parentCategory = $this->api->category()->get($this->parentCategory);
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

        return  $this->api->getCategories($this->parentCategory);
    }
}
 