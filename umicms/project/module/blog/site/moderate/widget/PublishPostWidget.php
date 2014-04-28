<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет публикации поста, требующего модерации.
 */
class PublishPostWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'publishModerateForm';
    /**
     * @var string|BlogPost $blogModerate пост или GUID поста, тербующего модерации
     */
    public $blogModerate;
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
        if (is_string($this->blogModerate)) {
            $this->blogModerate = $this->api->post()->getNeedModeratePost($this->blogModerate);
        }

        if (isset($this->blogModerate) && !$this->blogModerate instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogModerate',
                        'class' => 'BlogPost'
                    ]
                )
            );
        }

        $formPostModerate = $this->api->post()->getForm(
            BlogPost::FORM_CHANGE_POST_STATUS,
            IObjectType::BASE,
            $this->blogModerate
        );

        $formPostModerate->setAction($this->getUrl('publish', ['id' => $this->blogModerate->getId()]));
        $formPostModerate->setMethod('post');

        return $this->createResult(
            $this->template,
            [
                'form' => $formPostModerate
            ]
        );
    }
}
 