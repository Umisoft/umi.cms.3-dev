<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace functional\users\profile;

use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\FunctionalTester\CommonSteps;
use umitest\UrlMap;

/**
 * @guy umitest\FunctionalTester\CommonSteps
 */
class ProfileEditCest
{

    /**
     * @param CommonSteps|FunctionalTester $I
     */
    public function checkProfileEditForm(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->amOnPage(UrlMap::$userProfile);
        $I->seeLocalized(
            [
                'ru-RU' => 'Профиль',
                'en-US' => 'Profile'
            ],
            'h2'
        );
        $I->seeLocalized([
            'en-US' => 'Display name',
            'ru-RU' => 'Название'
        ], BlockMap::PROFILE_FORM_DISPLAY_NAME . ' label');
        $I->seeLocalized([
            'en-US' => 'E-mail',
            'ru-RU' => 'E-mail'
        ], BlockMap::PROFILE_FORM_EMAIL . ' label');
        $I->seeLocalized([
            'en-US' => 'First name',
            'ru-RU' => 'Имя'
        ], BlockMap::PROFILE_FORM_FIRST_NAME . ' label');
        $I->seeLocalized([
            'en-US' => 'Middle name',
            'ru-RU' => 'Отчество'
        ], BlockMap::PROFILE_FORM_MIDDLE_NAME . ' label');
        $I->seeLocalized([
            'en-US' => 'Last name',
            'ru-RU' => 'Фамилия'
        ], BlockMap::PROFILE_FORM_LAST_NAME . ' label');
        $I->seeElement(
            BlockMap::PROFILE_FORM_CSRF . ' input'
        , ['type' => 'hidden']);
        $I->seeLocalized([
            'en-US' => 'Save',
            'ru-RU' => 'Сохранить'
        ], BlockMap::PROFILE_FORM . ' button');
    }

} 