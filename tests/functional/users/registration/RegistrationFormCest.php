<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest\users\registration;

use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты формы регистрации пользователя со стороны сайта.
 */
class RegistrationFormCest
{
    /**
     * @param FunctionalTester $I
     */
    public function checkRegisterLinkAsGuest(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);

        $I->seeLinkLocalized(
            [
                'ru-RU' => 'Регистрация',
                'en-US' => 'Register'
            ],
            UrlMap::$userRegistration
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkRegisterFormValidators(FunctionalTester $I)
    {
        $I->haveRegisteredUser('TestUser');
        $I->amOnPage(UrlMap::$userRegistration);

        $I->submitForm(
            BlockMap::REGISTRATION_FORM,
            []
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            BlockMap::REGISTRATION_FORM_LOGIN_ERROR
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            BlockMap::REGISTRATION_FORM_PASSWORD_ERROR
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            BlockMap::REGISTRATION_FORM_EMAIL_ERROR
        );

        $I->submitForm(
            BlockMap::REGISTRATION_FORM,
            [
                'login' => 'TestUser',
                'password' => 'TestUser',
                'email' => 'TestUser'
            ]
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Указан некорректный email.',
                'en-US' => 'Wrong email format.'
            ],
            BlockMap::REGISTRATION_FORM_EMAIL_ERROR
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Пользователь с указанным логином уже существует.',
                'en-US' => 'Login is not unique.'
            ],
            BlockMap::REGISTRATION_FORM_LOGIN_ERROR
        );

    }
}
 