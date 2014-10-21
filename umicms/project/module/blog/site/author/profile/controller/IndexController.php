<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\profile\controller;

use umi\form\IForm;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogAuthor;

/**
 * Контроллер редактирования профиля автора блога.
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var string $template имя шаблона, по которому выводится результат
     */
    public $template = 'editProfile';
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
     * @param BlogModule $blogModule модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->module = $blogModule;
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
        $blogAuthor = $this->module->getCurrentAuthor();

        $form = $this->module->author()->getForm(
            BlogAuthor::FORM_EDIT_PROFILE,
            $blogAuthor->getTypeName(),
            $blogAuthor
        );
        $form->setAction($this->getUrl('index'));

        return $form;
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
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница редактирования профиля автора
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
 