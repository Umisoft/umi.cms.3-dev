<?php
namespace umitest\users;

use AspectMock\Test;
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
    public function loginWithIncorrectCredentials(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);

        $I->submitForm(
            '#users_authorization_loginForm',
            [
                'login'    => 'wrongLogin',
                'password' => 'wrongPassword'
            ]
        );

        $I->seeCurrentUrlEquals(UrlMap::$userAuthorization);
        $I->seeLocalized(
            [
                'ru-RU' => 'Неверный логин или пароль {projectUrl} {projectAbsoluteUrl}',
                'en-US' => 'Invalid login or password'
            ],
            '#users_authorization_login_errors'
        );
    }

}