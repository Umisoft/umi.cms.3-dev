<?php
namespace umitest\users;

use umi\http\Response;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты авторизации пользователя со стороны сайта.
 */
class RedirectCest
{
    /**
     * @param FunctionalTester $I
     */
    public function tryToCheckRedirectToMainPage(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$defaultUrl . '/main');
        $I->seeResponseCodeIs(Response::HTTP_MOVED_PERMANENTLY);
        $I->seeHttpHeader('Location', UrlMap::$defaultUrl);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToCheckNotFoundReponse(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$defaultUrl . '/123');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $I->amOnPage(UrlMap::$defaultUrl . '/456/');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToCheckRedirectFromSlash(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$defaultUrl . '/');
        $I->seeResponseCodeIs(Response::HTTP_MOVED_PERMANENTLY);
        $I->seeHttpHeader('Location', UrlMap::$defaultUrl);
    }
}