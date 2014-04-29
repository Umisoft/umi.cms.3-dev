<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogComment;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет добавления вывода формы добавления комментария.
 */
class BlogAddCommentWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'addComment';
    /**
     * @var string|BlogPost $blogPost пост или GUID поста
     */
    public $blogPost;
    /**
     * @var null|string|BlogComment $blogComment комментарий или GUID родительского комментария
     */
    public $blogComment = null;
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
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->api->post()->get($this->blogPost);
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

        if (is_string($this->blogComment)) {
            $this->blogComment = $this->api->comment()->get($this->blogComment);
        }

        if (isset($this->blogComment) && !$this->blogComment instanceof BlogComment) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogPost',
                        'class' => 'BlogComment'
                    ]
                )
            );
        }

        $comment = $this->api->comment()->add(null, IObjectType::BASE, $this->blogComment);

        $comment->post = $this->blogPost;

        $formAddComment = $this->api->comment()->getForm(BlogComment::FORM_ADD_COMMENT, IObjectType::BASE, $comment);

        $routeParams = [];
        if (isset($this->blogComment)) {
            $routeParams = ['parent' => $this->blogComment->getId()];
        }

        $formAddComment->setAction($this->getUrl('addComment', $routeParams));
        $formAddComment->setMethod('post');

        return $this->createResult(
            $this->template,
            [
                'form' => $formAddComment
            ]
        );
    }
}
 