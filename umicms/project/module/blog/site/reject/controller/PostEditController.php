<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\reject\controller;

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер редактирования отклонённого поста блога.
 */
class PostEditController extends BaseCmsController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var bool $success флаг указывающий на успешное сохранение изменений
     */
    private $success = false;

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
        return 'editPost';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $blogPost = $this->module->post()->getRejectedPostById($this->getRouteVar('id'));

        if (!$this->isAllowed($blogPost)) {
            throw new ResourceAccessForbiddenException(
                $blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->module->post()->getForm(
            BlogPost::FORM_EDIT_POST,
            IObjectType::BASE,
            $blogPost
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->getObjectPersister()->commit();
        $this->success = true;
    }

    protected function buildResponseContent()
    {
        return [
            'success' => $this->success
        ];
    }
}
 