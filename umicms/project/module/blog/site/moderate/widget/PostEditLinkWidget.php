<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет для вывода ссылки на редактирование поста, требующего модерации.
 */
class PostEditLinkWidget extends BaseLinkWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'editPostLink';
    /**
     * @var string|BlogPost $blogPost пост или GUID редактируемого поста, требующего модерации
     */
    public $blogPost;
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
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->api->post()->getNeedModeratePost($this->blogPost);
        }

        if (!$this->blogPost instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogPost',
                        'class' => 'BlogPost'
                    ]
                )
            );
        }

        return $this->getUrl('edit', ['id' => $this->blogPost->getId()], $this->absolute);
    }
}
 