<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft\controller;

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
 * Контроллер редактирования черновика блога.
 */
class BlogEditDraftController extends BaseAccessRestrictedController implements IFormAware, IObjectPersisterAware
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
        $blogDraft = $this->api->post()->getDraftById($id);

        if (!$this->isAllowed($blogDraft)) {
            throw new ResourceAccessForbiddenException(
                $blogDraft,
                $this->translate('Access denied')
            );
        }

        $form = $this->api->post()->getForm(BlogPost::FORM_EDIT_POST, IObjectType::BASE, $blogDraft);

        if ($this->isRequestMethodPost()) {

            $formData = $this->getAllPostVars();

            if ($form->setData($formData) && $form->isValid()) {

                $this->getObjectPersister()->commit();

                return $this->createRedirectResponse($this->getRequest()->getReferer());
            }
        }

        return $this->createViewResponse(
            'blogDraft',
            [
                'blogDraft' => $blogDraft,
                'form' => $form
            ]
        );
    }
}
 