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
 *
 */
class ProfileWidgetByGuestCest
{

    /**
     * @param FunctionalTester $I
     */
    public function guestCanNotSeeProfileLinkWidget(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->dontSeeLinkLocalized(
            [
                'ru-RU' => 'Редактировать профиль',
                'en-US' => 'Edit profile'
            ],
            UrlMap::$userEditProfile
        );

    }

    /**
     * @param FunctionalTester $I
     */
    public function guestCannotSeeProfileViewWidget(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->dontSeeLocalized(
            [
                'ru-RU' => "Добро пожаловать",
                'en-US' => "Welcome"
            ],
            BlockMap::AUTHORIZATION_WELCOME
        );
        $I->dontSeeLocalized(
            [
                'ru-RU' => 'Выйти',
                'en-US' => 'Log out'
            ],
            BlockMap::LOGOUT_FORM
        );
        $I->dontSeeLinkLocalized(
            [
                'ru-RU' => 'Редактировать профиль',
                'en-US' => 'Edit profile'
            ],
            UrlMap::$userEditProfile
        );
    }

} 