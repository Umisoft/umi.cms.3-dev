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
use umicms\hmvc\component\site\TFormSimpleController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;

/**
 * Контроллер снятия комментария с публикации.
 */
class UnpublishController extends BaseCmsController
{
    use TFormSimpleController;

    /**
     * @var BlogModule $api модуль "Блоги"
     */
    protected $model;

    /**
     * Конструктор.
     * @param BlogModule $blogModule модуль "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->model = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        return $this->model->comment()->getForm(BlogComment::FORM_UNPUBLISH_COMMENT, BlogComment::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->model->comment()->getById(
            $this->getRouteVar('id')
        )->unPublish();

        $this->commit();
    }
}
 