<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\author\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogAuthor;

/**
 * Виджет для вывода URL на RSS-ленту по автору.
 */
class BlogAuthorListRssLinkWidget extends BaseLinkWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'rssLink';
    /**
     * @var string|BlogAuthor $blogAuthor автор, для постов которого формировать RSS-ленту.
     */
    public $blogAuthor;
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
    protected function getLinkUrl()
    {
        if (is_string($this->blogAuthor)) {
            $this->blogAuthor = $this->api->author()->get($this->blogAuthor);
        }

        if (!$this->blogAuthor instanceof BlogAuthor) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogAuthor',
                        'class' => BlogAuthor::className()
                    ]
                )
            );
        }

        return $this->getUrl('rss', ['slug' => $this->blogAuthor->slug], $this->absolute);
    }

}
 