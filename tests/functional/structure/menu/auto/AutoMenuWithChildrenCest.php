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

use umicms\project\module\structure\model\object\StructureElement;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Tests for structure.menu.auto without children
 */
class AutoMenuWithChildrenCest
{

    const NEWS_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d959594628ce';
    const NEWS_SUBJECT_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462822';
    const NEWS_RUBRIC_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462811';
    const NEWS_ITEM_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462833';
    const BLOG_PAGE_GUID = 'e6b89f38-7af3-4bda-80fd-3d5a4cf080cf';


    /**
     * Проверяет, что страница новостей не отображена в меню,
     * т.к. ее свойство "Отображать в меню" не активно
     * @param FunctionalTester $I
     */
    public function blogPageIsNotInMenu(FunctionalTester $I)
    {
        $I->updatePagesStructure([
            self::BLOG_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => false,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->amOnPage(UrlMap::$projectUrl);

        $I->seeNumberOfElements('ul.nav.navbar-nav:not(.navbar-right) li', 3);

        $I->dontSeeLocalized([
            'en-US' => 'Blog',
            'ru-RU' => 'Блог'
        ], 'ul.nav.navbar-nav li');
    }

    /**
     * @param FunctionalTester $I
     */
    public function newsPageSubmenuAlwaysShowButSubPagesIsNotInMenu(FunctionalTester $I)
    {
        $I->updatePagesStructure([
            self::NEWS_ITEM_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => false,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->updatePagesStructure([
            self::NEWS_RUBRIC_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => false,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->updatePagesStructure([
            self::NEWS_SUBJECT_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => false,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->amOnPage(UrlMap::$projectUrl);

        $I->dontSeeLocalized([
            'en-US' => 'News rubric',
            'ru-RU' => 'Новостная рубрика'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->dontSeeLocalized([
            'en-US' => 'News subject',
            'ru-RU' => 'Новостной сюжет'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->dontSeeLocalized([
            'en-US' => 'News item',
            'ru-RU' => 'Новость'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');
    }

    /**
     * @param FunctionalTester $I
     */
    public function newsPageSubmenu(FunctionalTester $I)
    {
        $I->updatePagesStructure([
            self::NEWS_ITEM_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->updatePagesStructure([
            self::NEWS_RUBRIC_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->updatePagesStructure([
            self::NEWS_SUBJECT_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);

        $I->amOnPage(UrlMap::$projectUrl);

        $I->seeLocalized([
            'en-US' => 'News rubric',
            'ru-RU' => 'Новостная рубрика'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->seeLocalized([
            'en-US' => 'News subject',
            'ru-RU' => 'Новостной сюжет'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->seeLocalized([
            'en-US' => 'News item',
            'ru-RU' => 'Новость'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->updatePagesStructure([
            self::NEWS_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_CURRENT_SHOWN
            ],
            self::NEWS_ITEM_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ],
            self::NEWS_RUBRIC_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ],
            self::NEWS_SUBJECT_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]

        ]);
        $I->amOnPage(UrlMap::$projectUrl);

        $I->dontSeeLocalized([
            'en-US' => 'News rubric',
            'ru-RU' => 'Новостная рубрика'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->dontSeeLocalized([
            'en-US' => 'News subject',
            'ru-RU' => 'Новостной сюжет'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->dontSeeLocalized([
            'en-US' => 'News item',
            'ru-RU' => 'Новость'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->amOnPage(UrlMap::$projectUrl . '/news');

        $I->seeLocalized([
            'en-US' => 'News rubric',
            'ru-RU' => 'Новостная рубрика'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->seeLocalized([
            'en-US' => 'News subject',
            'ru-RU' => 'Новостной сюжет'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');

        $I->seeLocalized([
            'en-US' => 'News item',
            'ru-RU' => 'Новость'
        ], 'ul.nav.navbar-nav li ul.nav.navbar-nav li');
    }

}