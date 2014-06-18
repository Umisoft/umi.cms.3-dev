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

use umi\form\TFormAware;
use umi\form\IForm;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер добавления комментария.
 */
class IndexController extends BaseCmsController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

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
        $parentComment = $parentCommentId ? $this->module->comment()->getById($parentCommentId) : null;

        $post = $this->module->post()->getById($this->getPostVar('post'));

        $comment = $this->module->addComment(
            BlogComment::TYPE,
            $post,
            $parentComment
        );


        if ($this->isAllowed($comment, 'publish')) {
            $comment->published();
        } else {
            $comment->needModerate();
        }

        return $this->module->comment()->getForm(
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
 