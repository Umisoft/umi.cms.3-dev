<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\add\controller;

use umi\form\IForm;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\blog\model\object\CommentStatus;

/**
 * Контроллер добавления комментария.
 */
class AddController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var string|bool $added флаг, указывающий на публикацию комментария
     */
    private $added = false;
    /**
     * @var BlogComment $comment
     */
    private $comment;

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
    protected function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $type = $this->getRouteVar('type', BlogComment::TYPE_NAME);

        $parentCommentId = $this->getRouteVar('parent');
        $parentComment = $parentCommentId ? $this->module->comment()->getById($parentCommentId) : null;

        $post = $this->module->post()->getById($this->getPostVar('post'));

        $this->comment = $this->module->addComment(
            $type,
            $post,
            $parentComment
        );

        $this->comment->publishTime = new \DateTime();

        if ($this->isAllowed($this->comment, 'publish')) {
            $this->comment->status = $this->module->commentStatus()->get(CommentStatus::GUID_PUBLISHED);
        } else {
            $this->comment->status = $this->module->commentStatus()->get(CommentStatus::GUID_NEED_MODERATION);
        }

        $form = $this->module->comment()->getForm(
            $this->module->isAuthorRegistered() ? BlogComment::FORM_ADD_COMMENT : BlogComment::FORM_ADD_VISITOR_COMMENT,
            $type,
            $this->comment
        );

        $routeParams = ['type' => $type];
        if ($parentComment) {
            $routeParams['parent'] = $parentComment->getId();
        }
        $form->setAction($this->getUrl('add', $routeParams));

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();

        if ($this->comment->status->guid === CommentStatus::GUID_PUBLISHED) {
            $this->added = 'published';
        } elseif ($this->comment->status->guid === CommentStatus::GUID_NEED_MODERATION) {
            $this->added = 'moderation';
        }

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam string|bool $added флаг, указывающий на статус добавленного комментария:
     * published, если комментарий был добававлен и опубликован, moderation - если был добавлен и отправлен на модерацию, false, если комментарий не был добавлен
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница добавления комментария
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'added' => $this->added,
            'page' => $this->getCurrentPage()
        ];
    }
}
 