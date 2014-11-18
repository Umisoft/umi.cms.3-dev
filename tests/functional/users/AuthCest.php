<?php
namespace umitest\users;

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
        $I->amOnPage(UrlMap::$default);

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

        $I->amOnPage(UrlMap::$default);
        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'TestUser',
                'password' => 'TestUser'
            ]
        );

        $I->canSeeCurrentUrlEquals(UrlMap::$default);
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
}