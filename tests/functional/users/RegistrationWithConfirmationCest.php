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
 * Тесты формы регистрации пользователя с подтверждением.
 */
class RegistrationWithConfirmationCest
{

    /**
     * @param FunctionalTester $I
     */
    public function tryToRegisterWithConfirmation(FunctionalTester $I)
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
                'ru-RU' => 'Письмо с ключом активации было выслано на Ваш электронный адрес TestUser@example.com. Откройте письмо, чтобы завершить регистрацию.',
                'en-US' => 'Registration email sent to TestUser@example.com. Open this email to finish signup.',
            ]
        );

        $I->openEmail(
            'TestUser@example.com',
            [
                'ru-RU' => ': Подтверждение регистрации пользователя.',
                'en-US' => ': Confirm user registration.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Для того чтобы завершить регистрацию, перейдите по следующей ссылке',
                'en-US' => 'In order to complete your registration, visit the following URL',
            ]
        );

    }
}
 