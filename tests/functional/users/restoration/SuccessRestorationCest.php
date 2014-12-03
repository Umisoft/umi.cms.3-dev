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

/**
 * Class SuccessRestorationCest
 * @package functional\users\restoration
 */
class SuccessRestorationCest
{

    /**
     * @param FunctionalTester $I
     */
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

    /**
     * @param FunctionalTester $I
     */
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
                'en-US' => 'Restoration instruction was sent to your email'
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
                'en-US' => 'Password reset request was received for you account {projectAbsoluteUrl}. Please click the link below to reset you password'
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