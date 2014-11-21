<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest\users\authcookie;

use AspectMock\Test;
use umicms\project\module\users\model\UsersModule;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 *
 */
class LoginByAuthCookieCest
{
    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByCookieWithInvalidToken(FunctionalTester $I)
    {
        //TODO waiting for mail sender mock
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginWithExpiredAuthCookie(FunctionalTester $I)
    {
        Test::double('umicms\project\module\users\model\UsersModule', [
            'isUserCookieExpired' => true
        ]);

        $authCookie = $I->haveAuthCookieForUser($I->haveRegisteredUser());

        $I->setCookie(UsersModule::AUTH_COOKIE_NAME, $authCookie->getCookieValue());
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function canLoginByCookie(FunctionalTester $I)
    {
        Test::double('\umicms\project\module\users\model\object\UserAuthCookie', [
            'getCookieTTL' => function() { return new \DateTime(); }
        ]);
        $authCookie = $I->haveAuthCookieForUser($I->haveRegisteredUser());

        $I->setCookie(UsersModule::AUTH_COOKIE_NAME, $authCookie->getCookieValue());
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->seeCookie(UsersModule::AUTH_COOKIE_NAME);
        $I->seeLocalized(
            [
                'ru-RU' => 'Выйти',
                'en-US' => 'Log out'
            ],
            '#users_authorization_logoutForm_submit'
        );
    }
} 