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
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogCategory;

/**
 * Виджет для вывода URL на RSS-ленту по категории.
 */
class CategoryPostRssLinkWidget extends BaseLinkWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'rssLink';

    /**
     * @var BlogCategory|string|null $categories категория или GUID, URL на RSS которой генерировать.
     * Если не указана, генерируется URL на все посты.
     */
    public $category;

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
    protected function getLinkUrl()
    {
        if (is_string($this->category)) {
            $this->category = $this->module->category()->get($this->category);
        }

        if (isset($this->category) && !$this->category instanceof BlogCategory) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'category',
                        'class' => BlogCategory::className()
                    ]
                )
            );
        }

        return $this->getUrl('rss', ['url' => $this->category->getURL()], $this->absolute);
    }
}
 