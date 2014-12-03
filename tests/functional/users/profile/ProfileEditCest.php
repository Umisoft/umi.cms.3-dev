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
 * Тесты user.profile, user.profile.password
 * @guy umitest\FunctionalTester\CommonSteps
 */
class ProfileEditCest
{

    /**
     * Проверяет страницу профиля пользователя, виджеты, форму
     * @param CommonSteps|FunctionalTester $I
     */
    public function checkProfileEditForm(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->amOnPage(UrlMap::$userProfile);
        $I->seeLocalized([
            'ru-RU' => 'Профиль',
            'en-US' => 'Profile'
        ]);
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
            BlockMap::PROFILE_FORM_CSRF . ' input',
            ['type' => 'hidden']
        );
        $I->seeLocalized([
            'en-US' => 'Save',
            'ru-RU' => 'Сохранить'
        ], BlockMap::PROFILE_FORM . ' button');
        $I->seeLinkLocalized([
            'ru-RU' => 'Сменить пароль',
            'en-US' => 'Change password',
        ], UrlMap::$userProfilePass);
    }

    /**
     * Проверяет страницу смены пароля, смену пароля
     * @param CommonSteps|FunctionalTester $I
     */
    public function checkPasswordChangeForm(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->amOnPage(UrlMap::$userProfile);
        $I->click("a[href='" . UrlMap::$userProfilePass . "']");
        $I->seeLocalized([
            'ru-RU' => 'Смена пароля',
            'en-US' => 'Change password'
        ]);
        $I->seeLocalized([
            'ru-RU' => '',
            'en-US' => 'Password'
        ], BlockMap::PROFILE_PASSWORD_FORM_PASSWORD . ' label');
        $I->seeLocalized([
            'ru-RU' => '',
            'en-US' => 'Password'
        ], BlockMap::PROFILE_PASSWORD_FORM_NEW_PASSWORD . ' label');
        $I->seeElement(
            BlockMap::PROFILE_PASSWORD_FORM_CSRF . ' input',
            ['type' => 'hidden']
        );
        $I->seeElement(
            BlockMap::PROFILE_PASSWORD_FORM_REDIRECT_URL . ' input',
            ['type' => 'hidden']
        );
        $I->seeLocalized([
            'ru-RU' => 'Сохранить',
            'en-US' => 'Save'
        ], BlockMap::PROFILE_PASSWORD_FORM_SUBMIT);
        $I->seeElement(BlockMap::PROFILE_PASSWORD_FORM_SUBMIT);
        $I->submitForm(BlockMap::PROFILE_PASSWORD_FORM, [
            'password' => 'TestUser',
            'newPassword' => 'NewPassword']
        );
        $I->submitForm(BlockMap::LOGOUT_FORM, []);
        $I->amOnPage(UrlMap::$projectUrl);
        $I->login('TestUser', 'NewPassword');
        $I->seeLocalized([
            'ru-RU' => 'Добро пожаловать, TestUser',
            'en-US' => 'Welcome, TestUser'
        ], BlockMap::AUTHORIZATION_WELCOME);
    }

    /**
     * Сценарий - пользователь указал неверный старый пароль при смене пароля
     * @param CommonSteps|FunctionalTester $I
     */
    public function submitFormWithInvalidOldPassword(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->amOnPage(UrlMap::$userProfile);
        $I->click("a[href='" . UrlMap::$userProfilePass . "']");
        $I->seeLocalized([
            'ru-RU' => 'Смена пароля',
            'en-US' => 'Change password'
        ]);
        $I->submitForm(BlockMap::PROFILE_PASSWORD_FORM, [
            'password' => 'invalidPassword',
            'newPassword' => 'NewPassword'
        ]);
        $I->seeLocalized([
            'ru-RU' => 'Неверный пароль.',
            'en-US' => 'Wrong password.'
        ], BlockMap::PROFILE_PASSWORD_FORM_PASSWORD);
    }

    /**
     * Сценарий - пользователь указал пустой новый пароль при смене пароля
     * @param CommonSteps|FunctionalTester $I
     */
    public function submitChangePasswordFormWithNewPassword(FunctionalTester $I)
    {
        $I->haveRegisteredUser();
        $I->login('TestUser', 'TestUser');
        $I->amOnPage(UrlMap::$userProfile);
        $I->click("a[href='" . UrlMap::$userProfilePass . "']");
        $I->seeLocalized([
            'ru-RU' => 'Смена пароля',
            'en-US' => 'Change password'
        ]);
        $I->submitForm(BlockMap::PROFILE_PASSWORD_FORM, [
            'password' => 'TestUser',
            'newPassword' => ''
        ]);
        $I->seeLocalized([
            'ru-RU' => 'Значение поля обязательно для заполнения.',
            'en-US' => 'Value is required.'
        ], BlockMap::PROFILE_PASSWORD_FORM_NEW_PASSWORD);
    }

} 