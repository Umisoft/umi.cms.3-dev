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
use umi\http\Response;
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
    public function registerWithConfirmation(FunctionalTester $I)
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

        $I->openEmailMessage(
            'TestUser@example.com',
            [
                'ru-RU' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': Подтверждение регистрации пользователя.',
                'en-US' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': Confirm user registration.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Для того чтобы завершить регистрацию, перейдите по следующей ссылке',
                'en-US' => 'In order to complete your registration, visit the following URL',
            ]
        );

        $I->click(['xpath' => '//a']);

        $I->seeLocalized(
            [
                'ru-RU' => 'Ваш аккаунт успешно активирован',
                'en-US' => 'You have successfully activated your account',
            ]
        );

        $I->openEmailMessage(
            'TestNotification@example.com',
            [
                'ru-RU' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': Регистрация пользователя',
                'en-US' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': User registration.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Новый пользователь TestUser зарегистрировался на сайте ' . UrlMap::getProjectDomain() . UrlMap::$defaultUrl,
                'en-US' => 'New user TestUser has registered on the website ' . UrlMap::getProjectDomain() . UrlMap::$defaultUrl,
            ]
        );

    }

    public function emptyActivationCode(FunctionalTester $I)
    {
        echo UrlMap::getProjectDomain() . UrlMap::$userActivation, PHP_EOL;
        $I->amOnPage(UrlMap::getProjectDomain() . UrlMap::$userActivation);
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function incorrectActivationCode(FunctionalTester $I)
    {
        echo UrlMap::getProjectDomain() . UrlMap::$userActivation, PHP_EOL;
        $I->amOnPage(UrlMap::getProjectDomain() . UrlMap::$userActivation . '/incorrect');
        $I->seeLocalized(
            [
                'ru-RU' => 'Неверный код активации.',
                'en-US' => 'Wrong activation code format.',
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
                'getIsRegistrationWithActivation' => true
            ]
        );
        Test::double(
            'umicms\project\module\users\model\UsersModule',
            [
                'getNotificationRecipients' => ['TestNotification@example.com' => 'TestAdmin'],
            ]
        );
    }
}
 