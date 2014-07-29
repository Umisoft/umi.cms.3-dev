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

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\orm\metadata\IObjectType;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormSimpleController;

/**
 * Контроллер отправки поста на модерацию.
 */
class SendToModerationController extends BaseCmsController
{
    use TFormSimpleController;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var BlogPost $blogDraft черновик поста
     */
    protected $blogDraft;

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
        $this->blogDraft = $this->module->post()->getDraftById($this->getRouteVar('id'));

        if (!$this->isAllowed($this->blogDraft)) {
            throw new ResourceAccessForbiddenException(
                $this->blogDraft,
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
        $this->blogDraft->publishStatus = BlogPost::POST_STATUS_NEED_MODERATE;
        $this->commit();
    }
}
 