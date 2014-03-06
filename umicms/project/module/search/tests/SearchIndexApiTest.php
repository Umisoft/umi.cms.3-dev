<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\tests;

use umicms\project\module\search\api\SearchApi;
use umicms\project\module\search\api\SearchIndexApi;

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
