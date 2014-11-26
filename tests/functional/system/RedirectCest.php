<?php
namespace umitest\system;

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
    public function checkRedirectFromSlash(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$projectUrl . '/');
        $I->seeResponseCodeIs(Response::HTTP_MOVED_PERMANENTLY);
        $I->seeHttpHeader('Location', UrlMap::$projectUrl);
    }
    /**
     * @param FunctionalTester $I
     */
    public function checkRedirectToMainPage(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$defaultPageUrl);
        $I->seeResponseCodeIs(Response::HTTP_MOVED_PERMANENTLY);
        $I->seeHttpHeader('Location', UrlMap::$projectUrl);
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkNotFoundReponse(FunctionalTester $I)
    {
        $I->dontFollowRedirects();
        $I->amOnPage(UrlMap::$projectUrl . '/123');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $I->amOnPage(UrlMap::$projectUrl . '/456/');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

}