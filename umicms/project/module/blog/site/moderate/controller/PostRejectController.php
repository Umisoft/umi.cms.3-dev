<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\controller;

use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseSecureController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Контроллер отправки отклонённого поста на модерацию.
 */
class PostRejectController extends BaseSecureController implements IFormAware, IObjectPersisterAware
{
    use TFormAware;
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
     * @throws HttpNotFound
     * @return Response
     */
    public function __invoke()
    {
        if (!$this->isRequestMethodPost()) {
            throw new HttpNotFound('Page not found');
        }

        $form = $this->api->post()->getForm(BlogPost::FORM_REJECT_POST, IObjectType::BASE);
        $formData = $this->getAllPostVars();

        if ($form->setData($formData) && $form->isValid()) {
            $blogPost = $this->api->post()->getNeedModeratePostById($this->getRouteVar('id'));
            $blogPost->rejected();

            $this->getObjectPersister()->commit();

            return $this->createRedirectResponse($this->getRequest()->getReferer());
        } else {
            //TODO ajax
            var_dump($form->getMessages());
            exit();
        }
    }
}
 