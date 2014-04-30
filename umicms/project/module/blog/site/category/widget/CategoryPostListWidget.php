<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\category\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogCategory;

/**
 * Виджет для вывода списка постов по категориям.
 */
class CategoryPostListWidget extends BaseListWidget
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
        $categories = (array) $this->categories;

        foreach ($categories as &$category) {
            if (is_string($category)) {
                $category = $this->api->category()->get($category);
            }

            if (!$category instanceof BlogCategory) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'categories',
                            'class' => 'BlogCategory'
                        ]
                    )
                );
            }
        }

        return $this->api->getPostByCategory($categories);
    }
}
 