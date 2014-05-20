<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\authorization\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\site\controller\SitePageController;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер авторизации пользователя
 */
class LoginController extends SitePageController
{
    use TFormController;

    /**
     * @var UsersModule $api API модуля "Пользователи"
     */
    protected $api;

    /**
     * Конструктор.
     * @param UsersModule $usersModule API модуля "Пользователи"
     */
    public function __construct(UsersModule $usersModule)
    {
        $this->api = $usersModule;
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
        return $this->api->user()->getForm(AuthorizedUser::FORM_LOGIN_SITE, AuthorizedUser::TYPE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        if ($this->api->isAuthenticated()) {
            $this->api->logout();
        }

        /**
         * @var IFormElement $loginInput
         */
        $loginInput = $form->get(AuthorizedUser::FIELD_LOGIN);
        /**
         * @var IFormElement $passwordInput
         */
        $passwordInput = $form->get(AuthorizedUser::FIELD_PASSWORD);

        if ($this->api->login($loginInput->getValue(), $passwordInput->getValue())) {

            return $this->buildRedirectResponse();

        }

        $this->errors[] = $this->translate('Invalid login or password');

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'authenticated' => $this->api->isAuthenticated()
        ];
    }

}