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

/**
 * Tests for structure.menu.auto without children
 */
class AutoMenuWithChildrenCest
{

    const NEWS_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d959594628ce';
    const NEWS_SUBJECT_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462822';
    const NEWS_RUBRIC_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462811';
    const NEWS_ITEM_PAGE_GUID = '9ee6745f-f40d-46d8-8043-d95959462833';

    /**
     * @param FunctionalTester $I
     */
    public function testUpdatePagesStructure(FunctionalTester $I)
    {
        $I->updatePagesStructure([
            self::NEWS_PAGE_GUID => [
                StructureElement::FIELD_IN_MENU => true,
                StructureElement::FIELD_SUBMENU_STATE => StructureElement::SUBMENU_NEVER_SHOWN
            ]
        ]);
    }
} 