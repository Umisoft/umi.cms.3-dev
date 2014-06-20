<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace umicms\project\module\search\tests;

use umicms\project\module\search\model\SearchApi;
use umicms\project\module\search\model\SearchIndexApi;

/**
 * Tests for SearchApiTest
 */
class SearchIndexApiTest extends SearchTestCase
{
    /**
     * @var SearchApi $searchApi
     */
    protected $searchIndexApi;

    public function setUp()
    {
        parent::setUp();
        $api = new SearchIndexApi();
        $api->setCollectionManager($this->mockColectionManager());
        $api->setStemming($this->toolkit->getService('umi\stemming\IStemming'));
        $this->searchIndexApi = $api;
    }
    /**
     * @return SearchIndexApi
     */
    protected function getSearchIndexApi()
    {
        return $this->searchIndexApi;
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->searchIndexApi = null;
    }
    /**
     * @param string $raw
     * @param string $expect
     * @dataProvider provideNormalizeStrings
     */
    public function testNormalizeIndexString($raw, $expect)
    {
        $this->assertEquals(
            $expect,
            $this->getSearchIndexApi()->normalizeIndexString($raw),
            'Wrong index text normalizing'
        );
    }

    /**
     * @param string $text
     * @param string $expect
     * @dataProvider provideFilterSearchStrings
     */
    public function testFilterSearchableText($text, $expect)
    {
        $this->assertEquals(
            $expect,
            $this->getSearchIndexApi()->filterSearchableText($text),
            'Wrong search text filtering'
        );
    }

    /**
     * @return array
     */
    public function provideNormalizeStrings()
    {
        return [
            [
                'Побережье иллюстрирует холодный коралловый риф, при этом разрешен провоз 3 бутылок крепких спиртных '
                . 'напитков, 2 бутылок вина; 1 л духов в откупоренных флаконах, 2 л одеколона в откупоренных флаконах.',
                'ПОБЕРЕЖЬЕ ИЛЛЮСТРИРУЕТ ХОЛОДНЫЙ КОРАЛЛОВЫЙ РИФ ПРИ ЭТОМ РАЗРЕШЕН ПРОВОЗ 3 БУТЫЛОК КРЕПКИХ СПИРТНЫХ '
                . 'НАПИТКОВ 2 БУТЫЛОК ВИНА 1 Л ДУХОВ ОТКУПОРЕННЫХ ФЛАКОНАХ 2 Л ОДЕКОЛОНА ОТКУПОРЕННЫХ ФЛАКОНАХ',
            ],
            [
                'Побережье <b>иллюстрирует</b> холодный &laquo;коралловый&raquo; риф,',
                'ПОБЕРЕЖЬЕ ИЛЛЮСТРИРУЕТ ХОЛОДНЫЙ КОРАЛЛОВЫЙ РИФ',
            ],
            [
                '<xml><p>Побережье   иллюстрирует  холодный коралловый   риф, </xml>',
                'ПОБЕРЕЖЬЕ ИЛЛЮСТРИРУЕТ ХОЛОДНЫЙ КОРАЛЛОВЫЙ РИФ',
            ],
            [
                'а и б сидели на трубе',
                'СИДЕЛИ НА ТРУБЕ',
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideFilterSearchStrings()
    {
        return [
            [
                'Примерная <a href="blablabla">структура </a> маркетингового исследования,<br>конечно, парадоксально детерминирует креатив',
                'Примерная структура  маркетингового исследования, конечно, парадоксально детерминирует креатив',
            ]
        ];
    }
}
