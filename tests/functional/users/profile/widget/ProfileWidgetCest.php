<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace functional\users\profile\widget;

use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты виджетов user.profile
 * @guy umitest\FunctionalTester\CommonSteps
 */
class ProfileWidgetCest
{

    /**
     * Проверяет виджет ссылки на страницу профиля пользователя
     * @param FunctionalTester|FunctionalTester\CommonSteps $I
     */
    public function checkProfileLinkWidget(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->seeCurrentUrlEquals(UrlMap::$projectUrl);
        $I->seeLinkLocalized(
            [
                'ru-RU' => 'Редактировать профиль',
                'en-US' => 'Edit profile'
            ],
            UrlMap::$userEditProfile
        );
    }

    /**
     * Проверяет виджет user.profile.view
     * @param FunctionalTester|FunctionalTester\CommonSteps $I
     */
    public function checkProfileViewWidget(FunctionalTester $I)
    {
        $user = $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->seeLocalized(
            [
                'ru-RU' => "Добро пожаловать, {$user->displayName}",
                'en-US' => "Welcome, {$user->displayName}"
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

}