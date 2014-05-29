<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment\controller;

use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseSecureController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogComment;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер добавления комментария.
 */
class AddController extends BaseSecureController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

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
    protected function getTemplateName()
    {
        return 'addComment';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $parentCommentId = $this->getRouteVar('parent');
        $parentComment = $parentCommentId ? $this->api->comment()->getById($parentCommentId) : null;

        $post = $this->api->post()->getById($this->getPostVar('post'));

        $comment = $this->api->addComment(
            BlogComment::TYPE,
            $post,
            $parentComment
        );

        return $this->api->comment()->getForm(
            BlogComment::FORM_ADD_COMMENT,
            BlogComment::TYPE,
            $comment
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->getObjectPersister()->commit();

        return $this->buildRedirectResponse();
    }
}
 