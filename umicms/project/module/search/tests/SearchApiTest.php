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
use umicms\project\module\search\highlight\Fragment;

/**
 * Tests for SearchApiTest
 */
class SearchApiTest extends SearchTestCase
{
    /**
     * @var SearchApi $searchApi
     */
    protected $searchApi;

    public function setUp()
    {
        parent::setUp();
        $api = new SearchApi();
        $api->setCollectionManager($this->mockColectionManager());
        $api->setDbCluster($this->toolkit->getService('umi\dbal\cluster\IDbCluster'));
        $api->setStemming($this->toolkit->getService('umi\stemming\IStemming'));
        $this->searchApi = $api;
    }

    /**
     * @dataProvider provideSearchStrings
     * @param $raw
     * @param $result
     */
    public function testNormalizeSearchString($raw, $result)
    {
        $api = $this->getSearchApi();
        $this->assertEquals(
            $result,
            $api->normalizeSearchString($raw),
            'Query must be original group followed by grouped possible synonyms'
        );
    }

    /**
     * @dataProvider provideNormalizingStrings
     * @param $raw
     * @param $result
     */
    public function testNormalizeIndexString($raw, $result)
    {
        $api = $this->getSearchApi();
        $this->assertEquals($result, $api->normalizeIndexString($raw), 'String must be prepared correctly');
    }

    /**
     * @param $search
     * @param $text
     * @param $result
     * @dataProvider provideHighlightResults
     */
    public function testHighlightResults($search, $text, $result)
    {
        $api = $this->getSearchApi();
        $this->assertEquals(
            $result,
            $api->highlightResult($search, $text, '<mark>', '</mark>'),
            'Fragment must be marked correctly'
        );
    }

    /**
     * @param $search
     * @param $text
     * @param $margin
     * @param $results
     * @dataProvider provideHighlightFragmentsResults
     */
    public function testHighlightFragments($search, $text, $margin, $results)
    {
        $api = $this->getSearchApi();
        $fragments = $api->getResultFragmented($search, $text);
        $fragments->fragmentize($margin);
        $this->assertInstanceOf('umicms\project\module\search\highlight\Fragmenter', $fragments);
        /** @var $fragment Fragment */
        foreach ($fragments as $f => $fragment) {
            $this->assertTrue(isset($results[$f]), "Fragment result #$f must be fetched");
            $expectedFragment = $results[$f];
            $this->assertEquals($expectedFragment[0], $fragment->getEdgeLeft(), "Words before center must be found");
            $this->assertEquals($expectedFragment[1], $fragment->getCenter(), "Searched word must be found");
            $this->assertEquals($expectedFragment[2], $fragment->getEdgeRight(), "Words after must be found");
        }

    }

    /**
     * @return array
     */
    public function provideSearchStrings()
    {
        return [
            ['куплю сноуборды', '(КУПЛЮ СНОУБОРДЫ) ((КУПЛЯ КУПИТЬ) СНОУБОРД)'],
            ['сниму квартиру в центре, недорого', '(СНИМУ КВАРТИРУ ЦЕНТРЕ НЕДОРОГО) (СНЯТЬ КВАРТИРА ЦЕНТР НЕДОРОГО)',],
            ['душа монаха', '(ДУША МОНАХА) ((ДУШ ДУШИТЬ) МОНАХ)',],
            ['моющее средство (не кислота)', '(МОЮЩЕЕ СРЕДСТВО НЕ КИСЛОТА) ((МОЮЩИЙ МЫТЬ) СРЕДСТВО НЕ КИСЛОТА)',],
            ['диван-кровать + подушки', '(ДИВАН КРОВАТЬ ПОДУШКИ) (ДИВАН КРОВАТЬ ПОДУШКА)',],
            ['именительный падеж', 'ИМЕНИТЕЛЬНЫЙ ПАДЕЖ',],
        ];
    }

    /**
     * @return array
     */
    public function provideNormalizingStrings()
    {
        return [
            ['В "Правде" пишется правда. В «Известиях» — известия.', 'ПРАВДЕ ПИШЕТСЯ ПРАВДА ИЗВЕСТИЯХ ИЗВЕСТИЯ'],
            ['А поэта интересует и то, что будет через двести', 'ПОЭТА ИНТЕРЕСУЕТ ТО ЧТО БУДЕТ ЧЕРЕЗ ДВЕСТИ',],
        ];
    }

    /**
     * @return array
     */
    public function provideHighlightResults()
    {
        $on = '<mark>';
        $off = '</mark>';
        return [
            [
                'гольф',
                'не подтягивай гольфы до самого подбородка, оболтус',
                "не подтягивай {$on}гольф{$off}ы до самого подбородка, оболтус",
            ],
            [
                'мыть',
                'хорошо вымытый патиссон — залог крепкого здоровья',
                "хорошо {$on}вымытый{$off} патиссон — залог крепкого здоровья",
            ],
            [
                'души',
                'уж мы его душили, душили, вообрази себе, душа моя. Но всех не передушишь!',
                "уж мы его {$on}души{$off}ли, {$on}души{$off}ли, вообрази себе,"
                . " {$on}душа{$off} моя. Но всех не {$on}передушишь{$off}!",
            ],
            [
                'кон',
                'закончить не смогли вы кон, гоните бриллианты',
                "за{$on}кон{$off}чить не смогли вы {$on}кон,{$off} гоните бриллианты",
            ],
            [
                'выпросил корыто',
                "Выпросил, дурачина, корыто!\n В корыте много ль корысти?",
                "{$on}Выпросил,{$off} дурачина, {$on}корыто!{$off}\n В {$on}корыте{$off} много ль корысти?",
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideHighlightFragmentsResults()
    {
        $text = "Коммунальный модернизм теоретически возможен. В данном случае можно согласиться с А.А. Земляковским "
            . "и с румынским исследователем Альбертом Ковачем, считающими, что синекдоха иллюстрирует акцент, "
            . "потому что сюжет и фабула различаются.   Матрица   вероятна. Стиль, как справедливо считает И.Гальперин,"
            . " аллитерирует холодный цинизм, несмотря на отсутствие единого пунктуационного алгоритма. "
            . "Ритмический рисунок начинает деструктивный метаязык, тем не менее узус никак не предполагал здесь"
            . " родительного падежа.";
        $textRepeat = 'это было давно, но, говорят, было. Стиль, как справедливо считает И.Гальперин, '
            . 'аллитерирует холодный цинизм';
        return [
            [
                'матрица',
                $text,
                12,
                [
                    [
                        [
                            'Ковачем,',
                            'считающими,',
                            'что',
                            'синекдоха',
                            'иллюстрирует',
                            'акцент,',
                            'потому',
                            'что',
                            'сюжет',
                            'и',
                            'фабула',
                            'различаются.',
                        ],
                        'Матрица',
                        [
                            'вероятна.',
                            'Стиль,',
                            'как',
                            'справедливо',
                            'считает',
                            'И.Гальперин,',
                            'аллитерирует',
                            'холодный',
                            'цинизм,',
                            'несмотря',
                            'на',
                            'отсутствие',
                        ],
                    ]
                ]
            ],
            [
                'матрица',
                $text,
                3,
                [
                    [
                        ['и', 'фабула', 'различаются.',],
                        'Матрица',
                        ['вероятна.', 'Стиль,', 'как',],
                    ]
                ]
            ],
            [
                'было',
                $textRepeat,
                3,
                [
                    [
                        ['это'],
                        'было давно, но, говорят, было.',
                        ['Стиль,', 'как', 'справедливо',]
                    ],
                ]
            ],
            [
                'было',
                $textRepeat,
                2,
                [
                    [['это'], 'было', ['давно,', 'но,',]],
                    [['но,', 'говорят,',], 'было', ['Стиль,', 'как',]],
                ]
            ],
        ];
    }

    /**
     * @return SearchApi
     */
    protected function getSearchApi()
    {
        return $this->searchApi;
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->searchApi = null;
    }
}

