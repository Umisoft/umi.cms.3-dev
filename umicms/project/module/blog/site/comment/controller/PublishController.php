<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\controller;

use umi\form\IForm;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\hmvc\component\site\TFormSimpleController;
use umicms\project\module\blog\model\object\CommentStatus;

/**
 * Контроллер публикации комментария.
 */
class PublishController extends BaseCmsController
{
    use TFormSimpleController;

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
    protected function buildForm()
    {
        return $this->module->comment()->getForm(BlogComment::FORM_PUBLISH_COMMENT, BlogComment::TYPE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->module->comment()->getById($this->getRouteVar('id'))
            ->status = $this->module->commentStatus()->get(CommentStatus::GUID_PUBLISHED);

        $this->commit();
    }
}
 