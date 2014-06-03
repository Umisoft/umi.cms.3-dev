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

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\TFormSimpleController;

/**
 * Контроллер снятия поста с модерации и переноса в черновики.
 */
class PostDraftController extends BaseAccessRestrictedController implements IObjectPersisterAware
{
    use TFormSimpleController;
    use TObjectPersisterAware;

    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;
    /**
     * @var BlogPost $blogPost пост блога
     */
    protected $blogPost;

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
    protected function buildForm()
    {
        $this->blogPost = $this->api->post()->getNeedModeratePostById($this->getRouteVar('id'));

        if (!$this->isAllowed($this->blogPost)) {
            throw new ResourceAccessForbiddenException(
                $this->blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->api->post()->getForm(BlogPost::FORM_DRAFT_POST, IObjectType::BASE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->blogPost->draft();
        $this->getObjectPersister()->commit();
    }
}
 