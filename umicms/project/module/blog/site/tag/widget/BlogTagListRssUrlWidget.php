<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\tag\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogTag;

/**
 * Виджет для вывода URL на RSS-ленту по тэгу.
 */
class BlogTagListRssUrlWidget extends BaseSecureWidget
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
    public function __invoke()
    {
        if (is_string($this->blogTag)) {
            $this->blogTag = $this->api->tag()->get($this->blogTag);
        }

        if (isset($this->blogTag) && !$this->blogTag instanceof BlogTag) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'tag',
                        'class' => 'BlogTag'
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'url' => $this->getUrl('rss', ['slug' => $this->blogTag->slug])
            ]
        );
    }
}
 