<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\controller;

use umi\form\IForm;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер сохранения профиля пользователя
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;
    /**
     * @var bool $success флаг, указывающий на успешное сохранение изменений
     */
    private $success = false;

    /**
     * Конструктор.
     * @param UsersModule $module модуль "Пользователи"
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateName()
    {
        return 'index';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        return $this->module->user()->getForm(
            RegisteredUser::FORM_EDIT_PROFILE,
            RegisteredUser::TYPE_NAME,
            $this->module->getCurrentUser()
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
     * <ul>
     * <li> bool $success флаг, указывающий на успешное сохранение изменений </li>
     * <li> RegisteredUser $user текущий пользователь </li>
     * <li> ICmsPage $page текущая страница редактирования профиля пользователя </li>
     * </ul>
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'user' => $this->module->getCurrentUser(),
            'page' => $this->getCurrentPage(),
            'success' => $this->success
        ];
    }
}
 