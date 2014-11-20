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
use umicms\project\module\users\model\object\UserAuthCookie;
use umicms\project\module\users\model\UsersModule;
use umicms\Utils;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 *
 */
class InvalidAuthCookieCest
{
    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByCookieThatNotExists(FunctionalTester $I)
    {
        Test::double('umicms\project\module\users\model\UsersModule', ['getUserAuthCookie' => null]);
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            '1:27aa7b0a-cb33-4bfc-be10-26a654185d8f:26cccdec-65ab-4e94-90d3-2de2308e14b6'
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByAuthCookieWithoutAuthCookie(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userAuthByCookie);
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginByAuthCookieWithoutAuthCookieAndWithRefererParam(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryLoginWithInvalidCookie(FunctionalTester $I)
    {
        // invalid guid part
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            implode(UserAuthCookie::DELIMITER_CHAR, [1, 'invalid guid', Utils::generateGUID()])
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);

        // invalid token part
        $I->setCookie(
            UsersModule::AUTH_COOKIE_NAME,
            implode(UserAuthCookie::DELIMITER_CHAR, [1, Utils::generateGUID(), 'invalid token'])
        );
        $I->amOnPage(UrlMap::$userAuthByCookie . '?referer=' . UrlMap::$userRegistration);
        $I->seeCurrentUrlEquals(UrlMap::$userRegistration);
        $I->dontSeeCookie(UsersModule::AUTH_COOKIE_NAME);
    }
}