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

    public function checkRestoreLink(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$defaultPageUrl);
        $I->seeLinkLocalized(
            [
                'ru-RU' => 'Забыли пароль?',
                'en-US' => 'Forgot your password?'
            ],
            UrlMap::$userRestore
        );
    }

    public function restorationPassword(FunctionalTester $I)
    {
        $user = $I->haveRegisteredUser();
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
            $user->email,
            [
                'ru-RU' => '{projectAbsoluteUrl}: Подтверждение запроса смены пароля.',
                'en-US' => '{projectAbsoluteUrl}: Confirm password reset request.',
            ]
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Для Вашего аккаунта на {projectAbsoluteUrl} был сделан запрос на смену пароля. Чтобы сменить пароль перейдите по ссылке ниже',
                'en-US' => 'There has been a request to reset the password on your {projectAbsoluteUrl} account. To do this please click the link below'
            ]
        );

        $I->click(['xpath' => '//a']);

        $I->seeLocalized(
            [
                'ru-RU' => 'Новый пароль был выслан на Ваш электронный адрес',
                'en-US' => 'A new password was sent to your email'
            ]
        );
    }
} 