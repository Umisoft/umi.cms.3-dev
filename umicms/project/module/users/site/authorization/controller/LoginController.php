<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\authorization\controller;

use Symfony\Component\HttpFoundation\Cookie;
use umi\form\element\IFormElement;
use umi\form\IForm;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\hmvc\component\site\TFormController;

/**
 * Контроллер авторизации пользователя
 */
class LoginController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;

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
        $form = $this->module->user()->getForm(RegisteredUser::FORM_LOGIN_SITE, RegisteredUser::TYPE_NAME);

        $form->setAction($this->getUrl('login'));

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        if ($this->module->isAuthenticated()) {
            $this->module->logout();
        }

        /**
         * @var IFormElement $loginInput
         */
        $loginInput = $form->get(RegisteredUser::FIELD_LOGIN);
        /**
         * @var IFormElement $passwordInput
         */
        $passwordInput = $form->get(RegisteredUser::FIELD_PASSWORD);

        if ($this->module->login($loginInput->getValue(), $passwordInput->getValue())) {

            $response = $this->buildRedirectResponse();

            /** @var IFormElement $rememberMeInput */
            $rememberMeInput = $form->get(RegisteredUser::FORM_LOGIN_SITE_FIELD_REMEMBER_ME);
            if((bool) $rememberMeInput->getValue()) {
                $response->headers->setCookie($this->createAuthCookie());
                // Коммит auth-куки
                $this->commit();
            }

            return $response;
        }

        $this->errors[] = $this->translate('Invalid login or password');

        return null;
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $authenticated флаг, указывающий на то, авторизован пользователь или нет
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница авторизаци
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'page' => $this->getCurrentPage(),
            'authenticated' => $this->module->isAuthenticated()
        ];
    }

    /**
     * @return Cookie
     */
    private function createAuthCookie()
    {
        $user = $this->module->getAuthenticatedUser();
        $userAgentInfo = $this->getRequest()->headers->get('User-Agent');
        return new Cookie(
            UsersModule::AUTH_COOKIE_NAME,
            $this->module->createUserAuthCookie($user, $userAgentInfo)->getCookieValue(),
            $this->module->getAuthCookieTTL()
        );
    }
}