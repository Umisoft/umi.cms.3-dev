<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\add\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\blog\model\object\BlogPost;

/**
 * Виджет добавления вывода формы добавления комментария.
 */
class AddFormWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'addComment';
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = self::REFERER_REDIRECT;
    /**
     * @var string|BlogPost $blogPost пост или GUID поста
     */
    public $blogPost;
    /**
     * @var null|string|BlogComment $blogComment комментарий или GUID родительского комментария
     */
    public $blogComment = null;
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
    protected function getForm()
    {
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->module->post()->get($this->blogPost);
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

        if (is_string($this->blogComment)) {
            $this->blogComment = $this->module->comment()->get($this->blogComment);
        }

        if (isset($this->blogComment) && !$this->blogComment instanceof BlogComment) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogComment',
                        'class' => BlogComment::className()
                    ]
                )
            );
        }

        $comment = $this->module->comment()->add(null, BlogComment::TYPE, $this->blogComment);

        $comment->post = $this->blogPost;

        $form = $this->module->comment()->getForm(BlogComment::FORM_ADD_COMMENT, BlogComment::TYPE, $comment);

        $routeParams = isset($this->blogComment) ? ['parent' => $this->blogComment->getId()] : [];

        $form->setAction($this->getUrl('add', $routeParams));

        return $form;

    }
}
 