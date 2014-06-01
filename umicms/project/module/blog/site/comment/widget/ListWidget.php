<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\widget;

use umi\acl\IAclResource;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseTreeWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет для вывода списка коментов.
 */
class ListWidget extends BaseTreeWidget implements IAclResource
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * * @var string|BlogPost $blogPost GUID или пост блога, к которому необходимо вывести комментарии
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
    protected function getSelector()
    {
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->api->post()->get($this->blogPost);
        }

        if (!$this->blogPost instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogPost',
                        'class' => BlogPost::className()
                    ]
                )
            );
        }

        return $this->api->getCommentByPost($this->blogPost);
    }
}
 