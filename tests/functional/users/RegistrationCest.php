<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest\users;

use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты регистрации пользователя со стороны сайта.
 */
class RegistrationCest
{
    /**
     * @param FunctionalTester $I
     */
    public function checkRegisterLinkAsGuest(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$defaultUrl);

        $I->canSeeLinkLocalized(
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
            '#users_registration_index',
            []
        );
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            '#users_registration_index .login'
        );
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            '#users_registration_index .password'
        );
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            '#users_registration_index .email'
        );

        $I->submitForm(
            '#users_registration_index',
            [
                'login' => 'TestUser',
                'password' => 'TestUser',
                'email' => 'TestUser'
            ]
        );
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Указан некорректный email.',
                'en-US' => 'Wrong email format.'
            ],
            '#users_registration_index .email'
        );
        $I->canSeeLocalized(
            [
                'ru-RU' => 'Пользователь с указанным логином уже существует.',
                'en-US' => 'Login is not unique.'
            ],
            '#users_registration_index .login'
        );

    }
}
 