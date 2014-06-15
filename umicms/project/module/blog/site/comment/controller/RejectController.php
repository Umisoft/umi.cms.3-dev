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
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\site\controller\TFormSimpleController;

/**
 * Контроллер отклонения комментария.
 */
class RejectController extends BaseCmsController implements IObjectPersisterAware
{
    use TFormSimpleController;
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
    protected function buildForm()
    {
        return $this->module->comment()->getForm(BlogComment::FORM_REJECT_COMMENT, BlogComment::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $blogComment = $this->module->comment()->getById($this->getRouteVar('id'));
        $blogComment->rejected();

        $this->getObjectPersister()->commit();
    }
}
 