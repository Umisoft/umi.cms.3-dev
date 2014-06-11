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
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\TFormSimpleController;

/**
 * Контроллер отправки отклонённого поста на модерацию.
 */
class PostRejectController extends BaseAccessRestrictedController implements IObjectPersisterAware
{
    use TFormSimpleController;
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
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        return $this->api->post()->getForm(BlogPost::FORM_REJECT_POST, IObjectType::BASE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $blogPost = $this->api->post()->getNeedModeratePostById($this->getRouteVar('id'));
        $blogPost->reject();

        $this->getObjectPersister()->commit();
    }
}
 