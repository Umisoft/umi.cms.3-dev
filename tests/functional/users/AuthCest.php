<?php
namespace umitest\users;

use AspectMock\Test;
use umicms\project\module\users\model\object\UserAuthCookie;
use umicms\project\module\users\model\UsersModule;
use umicms\Utils;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты авторизации пользователя со стороны сайта.
 */
class AuthCest
{
    /**
     * @param FunctionalTester $I
     */
    public function tryToLoginWithIncorrectCredentials(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$defaultUrl);

        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'wrongLogin',
                'password' => 'wrongPassword'
            ]
        );

        $I->canSeeCurrentUrlEquals(UrlMap::$userAuthorization);
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Неверный логин или пароль',
                'en-US' => 'Invalid login or password'
            ],
            '#users_authorization_login_errors'
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToLoginWithCorrectCredentials(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');

        $I->amOnPage(UrlMap::$defaultUrl);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser'
            ]
        );

        $I->canSeeCurrentUrlEquals(UrlMap::$defaultUrl);
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Добро пожаловать, TestUser',
                'en-US' => 'Welcome, TestUser'
            ],
            '.authorization'
        );

        $I->canSeeLocalized(
            [
                'ru-RU' => 'Выйти',
                'en-US' => 'Log out'
            ],
            '#users_authorization_logoutForm'
        );

        $I->seeLinkLocalized(
            [
                'ru-RU' => 'Редактировать профиль',
                'en-US' => 'Edit profile'
            ],
            UrlMap::$userEditProfile
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function setAuthCookieWhenLoginWithRememberMe(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser',
                'rememberMe' => true
            ]
        );
        $I->seeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function doNotSetAuthCookieWhenLoginWithoutRememberMe(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser',
                'rememberMe' => false
            ]
        );

        $I->cantSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLogoutWithAuthCookie(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser',
                'rememberMe' => true
            ]
        );
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
     * @param FunctionalTester $I
     */
    public function tryLoginByAuthCookieWithoutAuthCookie(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userAuthByCookie);
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByAuthCookieWithoutAuthCookieAndWithRefererParam(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginWithInvalidCookie(FunctionalTester $I)
    {
        // invalid guid part
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            implode(UserAuthCookie::DELIMITER_CHAR, [1, 'invalid guid', Utils::generateGUID()])
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);

        // invalid token part
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            implode(UserAuthCookie::DELIMITER_CHAR, [1, Utils::generateGUID(), 'invalid token'])
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByCookieThatNotExists(FunctionalTester $I)
    {
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            '1:676bef31-f4dc-45f1-a68f-7bdde23c9aa1:df0100b0-b430-46ca-9a23-6297ad66acfc'
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByCookieWithInvalidToken(FunctionalTester $I)
    {
        //TODO
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByWithExpiredCookie(FunctionalTester $I)
    {
        //TODO
    }

    /**
     * @param FunctionalTester $I
     */
    public function canLoginByCookie(FunctionalTester $I)
    {
        //TODO
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        Test::double('umicms\project\module\users\model\UsersModule', ['getUserAuthCookie' => null]);
    }
}