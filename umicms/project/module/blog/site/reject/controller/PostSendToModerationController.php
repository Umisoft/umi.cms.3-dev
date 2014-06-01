<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\reject\controller;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Контроллер отправки поста на модерацию.
 */
class PostSendToModerationController extends BaseAccessRestrictedController implements IObjectPersisterAware
{
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
     * Вызывает контроллер.
     * @throws ResourceAccessForbiddenException если запрашиваемое действие запрещено
     * @throws HttpNotFound
     * @return Response
     */
    public function __invoke()
    {
        if (!$this->isRequestMethodPost()) {
            throw new HttpNotFound('Page not found');
        }

        $form = $this->api->post()->getForm(BlogPost::FORM_MODERATE_POST, IObjectType::BASE);
        $formData = $this->getAllPostVars();

        $blogPost = $this->api->post()->getRejectedPostById($this->getRouteVar('id'));
        if (!$this->isAllowed($blogPost)) {
            throw new ResourceAccessForbiddenException(
                $blogPost,
                $this->translate('Access denied')
            );
        }

        if ($form->setData($formData) && $form->isValid()) {
            $blogPost->needModeration();

            $this->getObjectPersister()->commit();

            return $this->createRedirectResponse($this->getRequest()->getReferer());
        } else {
            //TODO ajax
            var_dump($form->getMessages());
            exit();
        }
    }
}
 