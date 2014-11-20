<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest\users\authcookie;

use umicms\project\module\users\model\UsersModule;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты выставления\удаления auth-кук во время Login\Logout
 */
class SetUnsetAuthCookieCest
{

    /**
     * Тестирует, что auth-кука выставляется во время Login-а c чекнутой галочкой remember-me
     * @param FunctionalTester $I
     */
    public function setAuthCookieWhenLoginWithRememberMe(FunctionalTester $I)
    {
        $this->login($I, true);
        $I->seeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * Тестирует, что auth-кука не выставляется во время Login-а c un-чекнутой галочкой remember-me
     * @param FunctionalTester $I
     */
    public function doNotSetAuthCookieWhenLoginWithoutRememberMe(FunctionalTester $I)
    {
        $this->login($I, false);
        $I->cantSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * Тестирует, что auth-кука удаляется во время Logout-a
     * @param FunctionalTester $I
     */
    public function tryLogoutWithAuthCookie(FunctionalTester $I)
    {
        $this->login($I, true);
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Выйти',
                'en-US' => 'Log out'
            ],
            '#users_authorization_logoutForm_submit'
        );
        $I->click('#users_authorization_logoutForm_submit');
        $I->cantSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * Login action
     * @param FunctionalTester $I
     * @param bool             $withRememberMe запоминать или нет
     */
    protected function login(FunctionalTester $I, $withRememberMe)
    {
        $I->haveRegisteredUser('TestUser');
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser',
                'rememberMe' => $withRememberMe
            ]
        );
    }
} 