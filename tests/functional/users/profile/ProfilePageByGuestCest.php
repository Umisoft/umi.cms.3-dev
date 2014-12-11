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

use umi\http\Response;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты user.profile, user.profile.password под гостем
 */
class ProfilePageByGuestCest
{

    /**
     * Проверяет, что страница профиля пользователя не доступна для гостя
     * @param FunctionalTester $I
     */
    public function profilePageIsForbiddenForGuest(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userProfile);
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

    /**
     * Проверяет, что страница смены пароля не доступна для гостя
     * @param FunctionalTester $I
     */
    public function passwordChangeIsForbiddenForGuest(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$userProfilePass);
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

} 