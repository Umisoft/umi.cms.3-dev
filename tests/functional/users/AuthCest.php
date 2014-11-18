<?php
namespace umitest\users;

use umicms\project\module\users\model\UsersModule;
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
}