<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest\users;

use AspectMock\Test;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты формы регистрации пользователя с подтверждением.
 */
class RegistrationWithoutConfirmationCest
{

    /**
     * @param FunctionalTester $I
     */
    public function registerWithoutConfirmation(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRegistration);

        $I->submitForm(
            '#users_registration_index',
            [
                'login'      => 'TestUser',
                'password'   => 'TestUser',
                'email'      => 'TestUser@example.com',
                'firstName'  => 'TestFirstName',
                'middleName' => 'TestMiddleName',
                'lastName'   => 'TestLastName',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Вы успешно зарегистрировались и были авторизованы',
                'en-US' => 'You have successfully registered and logged in',
            ]
        );

        $I->openEmail(
            'TestUser@example.com',
            [
                'ru-RU' => ': Регистрация пользователя.',
                'en-US' => ': User registration.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Вы успешно зарегистрировались на сайте',
                'en-US' => 'You have successfully registered on the website',
            ]
        );

    }

    /**
     * Setup for test
     */
    public function _before()
    {
        Test::double(
            'umicms\project\module\users\model\collection\UserCollection',
            [
                'getIsRegistrationWithActivation' => false
            ]
        );
    }
}
 