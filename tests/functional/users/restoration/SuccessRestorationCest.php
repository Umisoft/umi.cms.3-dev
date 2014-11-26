<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace functional\users\restoration;

use umitest\FunctionalTester;
use umitest\UrlMap;

class SuccessRestorationCest
{

    public function restorationPassword(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->amOnPage(UrlMap::$userRestore);
        $I->submitForm(
            '#users_restoration_index',
            [
                'loginOrEmail' => 'TestUser'
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Письмо с инструкциями по сбросу пароля было выслано на Ваш электронный адрес.',
                'en-US' => 'An email with instructions on how to reset your password was sent to your email'
            ],
            '.alert.alert-success'
        );

        $I->openEmailMessage(
            'TestUser@example.com',
            [
                'ru-RU' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': Подтверждение запроса смены пароля.',
                'en-US' => UrlMap::getProjectDomain() . UrlMap::$defaultUrl . ': Confirm password reset request.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Для Вашего аккаунта на  ' . UrlMap::getProjectDomain() . '  был сделан запрос на смену пароля. Чтобы сменить пароль перейдите по ссылке ниже',
                'en-US' => 'There has been a request to reset the password on your ' . UrlMap::getProjectDomain() . ' account. To do this please click the link below'
            ]
        );

        $I->click(['xpath' => '//a']);
        $I->amOnPage('/php/users/restore/confirm/');
    }
} 