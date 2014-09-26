<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\edit\controller;

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер редактирования поста блога, требующего модерации.
 */
class EditController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var string $template имя шаблона, по которому выводится результат
     */
    public $template = 'editPost';

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var bool $success флаг, указывающий на успешное сохранение изменений
     */
    private $success = false;

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
    protected function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $blogPost = $this->module->post()->getNeedModeratePostById($this->getRouteVar('id'));

        if (!$this->isAllowed($blogPost)) {
            throw new ResourceAccessForbiddenException(
                $blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->module->post()->getForm(
            BlogPost::FORM_EDIT_POST,
            $blogPost->getTypeName(),
            $blogPost
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();
        $this->success = true;
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное сохранение изменений
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница редактирования поста
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'success' => $this->success,
            'page' => $this->getCurrentPage()
        ];
    }
}
 