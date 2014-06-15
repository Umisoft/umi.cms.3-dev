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
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormSimpleController;

/**
 * Контроллер отправки поста на модерацию.
 */
class PostSendToModerationController extends BaseCmsController implements IObjectPersisterAware
{
    use TFormSimpleController;
    use TObjectPersisterAware;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var BlogPost $blogPost пост блога
     */
    protected $blogPost;

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
        $this->blogPost = $this->module->post()->getRejectedPostById($this->getRouteVar('id'));

        if (!$this->isAllowed($this->blogPost)) {
            throw new ResourceAccessForbiddenException(
                $this->blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->module->post()->getForm(BlogPost::FORM_MODERATE_POST, IObjectType::BASE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->blogPost->needModeration();
        $this->getObjectPersister()->commit();
    }
}
 