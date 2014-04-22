<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет публикации черновика.
 */
class BlogPostDraftWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'postDraftForm';
    /**
     * @var string|BlogPost $blogPost черновик или GUID черновика
     */
    public $blogDraft;
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
        if (is_string($this->blogDraft)) {
            $this->blogDraft = $this->api->post()->getDraft($this->blogDraft);
        }

        if (isset($this->blogDraft) && !$this->blogDraft instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param} should be instance of "{class}".',
                    [
                        'param' => 'blogDraft',
                        'class' => 'BlogPost'
                    ]
                )
            );
        }

        $formPostDraft = $this->api->post()->getForm(BlogPost::FORM_DRAFT_POST, IObjectType::BASE, $this->blogDraft);

        $formPostDraft->setAction($this->getUrl('postDraft'));
        $formPostDraft->setMethod('post');

        return $this->createResult(
            $this->template,
            [
                'form' => $formPostDraft
            ]
        );
    }
}
 