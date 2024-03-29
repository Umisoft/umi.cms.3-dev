<?php
namespace umitest\users;

use AspectMock\Test;
use umitest\BlockMap;
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
            BlockMap::AUTHORIZATION_FORM,
            [
                'login'    => 'wrongLogin',
                'password' => 'wrongPassword'
            ]
        );

        $I->seeCurrentUrlEquals(UrlMap::$userAuthorization);
        $I->seeLocalized(
            [
                'ru-RU' => 'Неверный логин или пароль',
                'en-US' => 'Invalid login or password'
            ],
            BlockMap::AUTHORIZATION_FORM_ERRORS
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginWithCorrectCredentials(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');

        $I->amOnPage(UrlMap::$projectUrl);
        $I->submitForm(
            BlockMap::AUTHORIZATION_FORM,
            [
                'login'    => 'TestUser',
                'password' => 'TestUser'
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$projectUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Добро пожаловать, TestUser',
                'en-US' => 'Welcome, TestUser'
            ],
            BlockMap::AUTHORIZATION_WELCOME
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Выйти',
                'en-US' => 'Log out'
            ],
            BlockMap::LOGOUT_FORM
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
     * Проверяет, что производится редирект если не указан referer
     * @param FunctionalTester $I
     */
    public function logoutWithoutRefererHeader(FunctionalTester $I)
    {
        Test::double('\umicms\project\module\users\site\authorization\controller\LogoutController',
            [
                'getReferer' => null,
            ]
        );

        $this->doLoginAndLogout($I);
        $I->seeCurrentUrlEquals(UrlMap::$userAuthorization);
    }

    /**
     * Проверяет, что учитывается referer
     * @param FunctionalTester $I
     */
    public function logoutWithRefererHeader(FunctionalTester $I)
    {
        Test::double('\umicms\project\module\users\site\authorization\controller\LogoutController',
            [
                'getReferer' => UrlMap::$projectUrl,
                'getProjectUrl' => UrlMap::$projectUrl
            ]
        );

        $this->doLoginAndLogout($I);
        $I->seeCurrentUrlEquals(UrlMap::$projectUrl);
    }

    /**
     * Проверяет logout с referer-ом, указывающим на текущую страницу, что циклический редирект отсутствует
     * @param FunctionalTester $I
     */
    public function logoutWhenRefererEqualsCurrentUrl(FunctionalTester $I)
    {
        Test::double('\umicms\project\module\users\site\authorization\controller\LogoutController',
            [
                'getReferer' => UrlMap::$projectUrl,
                'getCurrentUrl' => UrlMap::$projectUrl,
                'getProjectUrl' => UrlMap::$projectUrl
            ]
        );
        $this->doLoginAndLogout($I);
        $I->seeCurrentUrlEquals(UrlMap::$userAuthorization);
    }

    /**
     * Проверяет корректность работы с "плохим" referer-ом, указывающим на другой сайт
     * @param FunctionalTester $I
     */
    public function logoutWithBadReferer(FunctionalTester $I)
    {
        Test::double('\umicms\project\module\users\site\authorization\controller\LogoutController',
            [
                'getReferer' => 'http://bad.ru/' . UrlMap::$projectUrl,
                'getCurrentUrl' => UrlMap::$projectUrl,
                'getProjectUrl' => 'http://good.ru/' . UrlMap::$projectUrl
            ]
        );
        $this->doLoginAndLogout($I);
        $I->seeCurrentUrlEquals(UrlMap::$userAuthorization);
    }

    /**
     * @param FunctionalTester $I
     */
    private function doLoginAndLogout(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');

        $I->amOnPage(UrlMap::$projectUrl);
        $I->submitForm(
            BlockMap::AUTHORIZATION_FORM,
            [
                'login'    => 'TestUser',
                'password' => 'TestUser'
            ]
        );
        $I->submitForm(BlockMap::LOGOUT_FORM, []);
        $I->seeLocalized(            [
            'ru-RU' => 'Войти',
            'en-US' => 'Log in'
        ], BlockMap::AUTHORIZATION_FORM);
    }
}