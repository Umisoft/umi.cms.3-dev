<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\password\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\project\module\users\site\profile\password\model\PasswordValidator;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер изменения пароля пользователя
 */
class IndexController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;
    /**
     * @var bool $success успех операции запроса смены пароля
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
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $user = $this->module->getAuthenticatedUser();
        $form = $this->module->user()->getForm(RegisteredUser::FORM_CHANGE_PASSWORD, $user->getTypeName(), $user);

        /**
         * @var IFormElement $passwordInput
         */
        $passwordInput = $form->get('password');

        $passwordValidator = new PasswordValidator(
            'password',
            [
                'salt' => $user->getProperty(RegisteredUser::FIELD_PASSWORD_SALT)->getValue(),
                'hash' => $user->getProperty(RegisteredUser::FIELD_PASSWORD)->getValue(),
                'errorLabel' => 'Wrong password.',
                'message' => $this->translate('Wrong password.')
            ]
        );
        $passwordInput->getValidators()->appendValidator($passwordValidator);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->success = true;
        $this->commit();

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное сохранение изменений
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница изменения пароля пользователя
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'success' => $this->success
        ];
    }

}
 