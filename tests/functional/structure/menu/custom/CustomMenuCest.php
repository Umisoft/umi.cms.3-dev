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
 * Тесты structure.menu.custom
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

    /**
     * Проверяет свойства active, current
     * @param FunctionalTester $I
     */
    public function checkActiveAndCurrentProperties(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->clickLocalized([
            'en-US' => 'Concerts',
            'ru-RU' => 'Наши концерты'
        ], BlockMap::BOTTOM_MENU_ELEMENT);

        // один элемент меню имеет свойства current и active
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT, 4);
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT . ' a', 3);
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT . '[class="active"]', 1);
        $I->seeLocalized([
            'en-US' => 'Concerts',
            'ru-RU' => 'Наши концерты'
        ], BlockMap::BOTTOM_MENU_ELEMENT . '[class="active"]');

        $I->amOnPage(UrlMap::$projectUrl . '/news/item/moscow-umi-rock-band');

        // один элемент меню имеет свойство active
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT, 4);
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT . ' a', 4);
        $I->canSeeNumberOfElements(BlockMap::BOTTOM_MENU_ELEMENT . '[class="active"] a', 1);
        $I->seeLocalized([
            'en-US' => 'Concerts',
            'ru-RU' => 'Наши концерты'
        ], BlockMap::BOTTOM_MENU_ELEMENT . '[class="active"] a');
    }

} 