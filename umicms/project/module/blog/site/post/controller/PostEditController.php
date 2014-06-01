<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\controller;

use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\http\Response;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Контроллер редактирования поста блога.
 */
class PostEditController extends BaseAccessRestrictedController implements IFormAware, IObjectPersisterAware
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
     * @throws ResourceAccessForbiddenException если запрашиваемое действие запрещено
     * @return Response
     */
    public function __invoke()
    {
        $id = $this->getRouteVar('id');
        $blogPost = $this->api->post()->getById($id);

        if (!$this->isAllowed($blogPost)) {
            throw new ResourceAccessForbiddenException(
                $blogPost,
                $this->translate('Access denied')
            );
        }

        $form = $this->api->post()->getForm(BlogPost::FORM_EDIT_POST, IObjectType::BASE, $blogPost);

        if ($this->isRequestMethodPost()) {

            $formData = $this->getAllPostVars();

            if ($form->setData($formData) && $form->isValid()) {

                $this->getObjectPersister()->commit();

                return $this->createRedirectResponse($this->getRequest()->getReferer());
            }
        }

        return $this->createViewResponse(
            'editPost',
            [
                'blogPost' => $blogPost,
                'form' => $form->getView()
            ]
        );
    }
}
 