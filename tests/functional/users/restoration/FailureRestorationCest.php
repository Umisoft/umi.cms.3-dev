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

use umi\http\Response;
use umicms\Utils;
use umitest\FunctionalTester;
use umitest\UrlMap;

class FailureRestorationCest
{

    public function submitEmptyForm(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRestore);
        $I->submitForm(
            '#users_restoration_index', []
        );

        $I->seeLocalized(
            [
                'ru-RU' => 'Значение поля обязательно для заполнения.',
                'en-US' => 'Value is required.'
            ],
            '#users_restoration_index .loginOrEmail'
        );
    }

    public function submitBadToken(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRestore);
        $I->submitForm(
            '#users_restoration_index',
            [
                'csrf' => 'bad csrf'
            ]
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Недопустимый маркер CSRF.',
                'en-US' => 'Invalid csrf token.'
            ],
            '#users_restoration_index .csrf'
        );
    }

    public function restorationForNonexistentUser(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRestore);
        $I->submitForm(
            '#users_restoration_index',
            [
                'loginOrEmail' => 'nonexistentUser'
            ]
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Пользователя с заданным логином или email не существует.',
                'en-US' => 'User with given login or email does not exist.'
            ],
            '#users_restoration_index_errors'
        );
    }

    public function restorationForNonActivatedUser(FunctionalTester $I)
    {
        $user = $I->haveRegisteredUser();
        $user->active = false;
        $I->grabOrmObjectPersister()->markAsModified($user)->commit();

        $I->amOnPage(UrlMap::$userRestore);
        $I->submitForm(
            '#users_restoration_index',
            [
                'loginOrEmail' => 'TestUser'
            ]
        );
        $I->seeLocalized(
            [
                'ru-RU' => 'Пользователь с заданным логином или email не активирован или заблокирован.',
                'en-US' => 'User with given login or email has been block or has not activated.'
            ],
            '#users_restoration_index_errors'
        );
    }

    public function restoreWithWrongActivationCodeFormat(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRestoreConfirm . '/bad-activation-code-format');
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeLocalized(
            [
                'ru-RU' => 'Wrong activation code format.',
                'en-US' => 'Wrong activation code format.'
            ],
            '.alert.alert-danger'
        );
    }

    public function restoreWithNonExistentActivationCode(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userRestoreConfirm . '/' . Utils::generateGUID());
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeLocalized(
            [
                'ru-RU' => 'Cannot find user by activation code.',
                'en-US' => 'Cannot find user by activation code.'
            ],
            '.alert.alert-danger'
        );
    }
} 