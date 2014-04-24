<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\post\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет добавления поста.
 */
class BlogAddPostWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'addPostForm';
    /**
     * @var string|BlogCategory $blogCategory рубрика или GUID родительской рубрики
     */
    public $blogCategory;
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
        if (is_string($this->blogCategory)) {
            $this->blogCategory = $this->api->category()->get($this->blogCategory);
        }

        if (isset($this->blogCategory) && !$this->blogCategory instanceof BlogCategory) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogCategory',
                        'class' => 'BlogCategory'
                    ]
                )
            );
        }

        $post = $this->api->post()->add();
        $post->category = $this->blogCategory;

        $formAddPost = $this->api->post()->getForm(BlogPost::FORM_ADD_POST, IObjectType::BASE, $post);

        $formAddPost->setAction($this->getUrl('add'));
        $formAddPost->setMethod('post');

        return $this->createResult(
            $this->template,
            [
                'form' => $formAddPost
            ]
        );
    }
}
 