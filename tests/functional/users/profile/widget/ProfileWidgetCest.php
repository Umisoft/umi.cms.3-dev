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

use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * @guy umitest\FunctionalTester\CommonSteps
 */
class ProfileWidgetCest
{

    /**
     * @param FunctionalTester|FunctionalTester\CommonSteps $I
     */
    public function checkEditProfile(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->seeCurrentUrlEquals(UrlMap::$projectUrl);
        $I->seeLinkLocalized(
            [
                'ru-RU' => 'Редактировать профиль',
                'en-US' => 'Edit profile'
            ],
            UrlMap::$userRestore
        );
    }

}