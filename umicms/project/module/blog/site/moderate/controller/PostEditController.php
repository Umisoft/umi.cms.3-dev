<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\moderate\controller;

use umi\form\IForm;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseSecureController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер редактирования поста блога, требующего модерации.
 */
class PostEditController extends BaseSecureController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;
    /**
     * @var bool $success флаг указывающий на успешное сохранение изменений
     */
    private $success = false;

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
        return 'editPost';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $id = $this->getRouteVar('id');
        $blogPost = $this->api->post()->getNeedModeratePostById($id);

        return $this->api->post()->getForm(
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

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'success' => $this->success
        ];
    }
}
 