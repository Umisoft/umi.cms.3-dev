<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace functional\structure\menu\auto;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Tests for structure.menu.auto without children
 */
class AutoMenuWithoutChildrenCest
{

    /**
     * Проверяет генерацию автоменю по дефолтной структуре
     * Главная|Блог|О нас|Новости
     * @param FunctionalTester $I
     */
    public function checkDefaultAutoMenu(FunctionalTester $I)
    {

        $I->amOnPage(UrlMap::$projectUrl . '/blog/post/view/russian-rock');

        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li', 4);
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li[class="active"]', 1);
        $I->seeLocalized([
            'en-US' => 'Blog',
            'ru-RU' => 'Блог'
        ], 'ul.nav.navbar-nav li[class="active"]');

        $I->amOnPage(UrlMap::$projectUrl . '/news');
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li', 4);
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li[class="active"]', 1);
        $I->seeLocalized([
            'en-US' => 'News',
            'ru-RU' => 'Новости'
        ], 'ul.nav.navbar-nav li[class="active"]');

        $I->amOnPage(UrlMap::$projectUrl . '/about');
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li', 4);
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li[class="active"]', 1);
        $I->seeLocalized([
            'en-US' => 'About us',
            'ru-RU' => 'О нас'
        ], 'ul.nav.navbar-nav li[class="active"]');


        $I->amOnPage(UrlMap::$projectUrl);
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li', 4);
        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li[class="active"]', 1);

        $I->seeLinkLocalized([
            'en-US' => 'Main page',
            'ru-RU' => 'Главная'
        ], UrlMap::$projectUrl);

        $I->seeLinkLocalized([
            'en-US' => 'Blog',
            'ru-RU' => 'Блог'
        ], UrlMap::$projectUrl . '/blog');

        $I->seeLinkLocalized([
            'en-US' => 'About us',
            'ru-RU' => 'О нас'
        ], UrlMap::$projectUrl . '/about');

        $I->seeLinkLocalized([
            'en-US' => 'News',
            'ru-RU' => 'Новости'
        ], UrlMap::$projectUrl . '/news');

        $I->seeLocalized([
            'en-US' => 'Main page',
            'ru-RU' => 'Главная'
        ], 'ul.nav.navbar-nav li[class="active"]');

    }
} 