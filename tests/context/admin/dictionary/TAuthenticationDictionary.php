<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context\admin\dictionary;

use tests\context\admin\page\AuthFormPage;
use tests\context\BaseCmsContext;

/**
 * Словарь для авторизации в административной панели
 * @mixin BaseCmsContext
 */
trait TAuthenticationDictionary
{
    /**
     * @var AuthFormPage $authPage страница авторизации
     */
    protected $authPage;

    /**
     * @Given я на странице авторизации административной панели
     * @return AuthFormPage
     */
    public function iAmOnAdminAuthPage()
    {
        return $this->browsePage(AuthFormPage::className());
    }

    /**
     * @Given я авторизован в административной панели с логином :login и паролем :password
     * @param string $login
     * @param string $password
     * @throws \Exception если не удалось авторизоваться
     * @return AuthFormPage
     */
    public function iAuthenticateWithLoginAndPassword($login, $password)
    {
        $page = $this->iTryAuthenticateWithLoginAndPassword($login, $password);
        if (!$page->isAuthenticated()) {
            $this->fail();
        }

        return $page;
    }

    /**
     * @When /^я пытаюсь авторизоваться в административной панели с логином "([^"]*)" и паролем "([^"]*)"$/
     * @param string $login
     * @param string $password
     * @return AuthFormPage
     */
    public function iTryAuthenticateWithLoginAndPassword($login = "", $password = "")
    {
        return $this->iAmOnAdminAuthPage()
            ->fillLogin($login)
            ->fillPassword($password)
            ->submitAuthForm();
    }

    /**
     * @Then я должен быть успешно авторизован в административной панели
     */
    public function iShouldBeAuthorizedAsAdministrator()
    {
        if (!$this->getAuthPage()->isAuthenticated()) {
            $this->fail();
        }
    }

    /**
     * @Then я должен видеть ошибку сервера :message
     */
    public function iShouldSeeServerError($message)
    {
        if (!$this->getAuthPage()->checkForServerError($message)) {
            $this->fail();
        }
    }

    /**
     * Возвращает страницу авторизации.
     * @throws \Exception если страница авторизации не найдена, либо не открыта.
     * @return AuthFormPage
     */
    protected function getAuthPage()
    {
        $page = $this->getPage(AuthFormPage::className());
        if (!$page instanceof AuthFormPage || !$page->isCurrent()) {
            throw new \Exception('AuthPage is not found or not opened.');
        }

        return $page;
    }
}
 