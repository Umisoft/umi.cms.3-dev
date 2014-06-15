<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\tag\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogTag;

/**
 * Виджет для вывода URL на RSS-ленту по тэгу.
 */
class TagListRssLinkWidget extends BaseLinkWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'rssLink';

    /**
     * @var string|BlogTag $blogTag тэг блога или GUID, по которому формируется RSS-лента
     */
    public $blogTag;

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
        if (is_string($this->blogTag)) {
            $this->blogTag = $this->module->tag()->get($this->blogTag);
        }

        if (!$this->blogTag instanceof BlogTag) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogTag',
                        'class' => BlogTag::className()
                    ]
                )
            );
        }

        return $this->getUrl('rss', ['slug' => $this->blogTag->slug]);
    }
}
 