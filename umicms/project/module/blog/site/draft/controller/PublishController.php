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
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormSimpleController;
use umicms\project\module\blog\model\object\PostStatus;

/**
 * Контроллер публикации черновика.
 *
 * Контроллер обрабатывает POST-запрос на перемещение черновика в опубликованные посты и не имеет шаблонизируемого ответа.
 * В случае успешного выполнения операции контроллер производит редирект на URL, указанный в запросе, или на реферер.
 * Если нет возможности выполнить редирект, контроллер возвращает простое текстовое сообщение об успехе.
 * Если операцию выполнить не удалось, выбрасывается исключение.
 */
class PublishController extends BaseCmsController
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

        return $this->module->post()->getForm(BlogPost::FORM_PUBLISH_POST, $this->blogDraft->getTypeName());
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->blogDraft->status = $this->module->postStatus()->get(PostStatus::GUID_PUBLISHED);
        $this->commit();
    }
}
 