<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace functional\structure\menu\custom;

use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Class FirstCest
 * @package functional\structure\menu\custom
 */
class CustomMenuCest
{

    /**
     * Проверяет количество пунктов custom меню
     * @param FunctionalTester $I
     */
    public function checkSimpleMenu(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT, 4);
        $I->seeLinkLocalized([
            'en-US' => 'Hobbies',
            'ru-RU' => 'Хобби'
        ], '{projectUrl}/news/rubric/hobby');
        $I->seeLinkLocalized([
            'en-US' => 'Concerts',
            'ru-RU' => 'Наши концерты'
        ], '{projectUrl}/news/rubric/concerts');
        $I->seeLinkLocalized([
            'en-US' => 'Blog',
            'ru-RU' => 'Блог'
        ], '{projectUrl}/blog/categories/about-music');
        $I->seeLinkLocalized([
            'en-US' => 'Roots',
            'ru-RU' => 'Сердце сайта'
        ], 'http://umi-cms.ru');
        $I->seeLocalized([
            'en-US' => 'Roots',
            'ru-RU' => 'Сердце сайта'
        ], 'a[href="http://umi-cms.ru"][target="blank"]');
    }

} 