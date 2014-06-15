<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\reject\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;

/**
 * Виджет для вывода ссылки на редактирование отклонённого поста.
 */
class PostEditLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'editPostLink';
    /**
     * @var string|BlogPost $blogPost пост или GUID отклонённого поста
     */
    public $blogPost;
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
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->module->post()->getRejectedPost($this->blogPost);
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

        return $this->getUrl('edit', ['id' => $this->blogPost->getId()], $this->absolute);
    }
}
 