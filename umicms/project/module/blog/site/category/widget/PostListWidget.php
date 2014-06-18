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
 * Виджет для вывода списка постов по категориям.
 */
class PostListWidget extends BaseListWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'postList';
    /**
     * @var array|BlogCategory[]|BlogCategory|null $category категория, список категорий блога или GUID, из которых выводятся посты.
     * Если не указаны, то посты выводятся из всех категорий
     */
    public $categories;

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
        $categories = (array) $this->categories;

        foreach ($categories as &$category) {
            if (is_string($category)) {
                $category = $this->module->category()->get($category);
            }

            if (!$category instanceof BlogCategory) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'categories',
                            'class' => BlogCategory::className()
                        ]
                    )
                );
            }
        }

        return $this->module->getPostByCategory($categories);
    }
}
 