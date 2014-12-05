<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\unit\metatag\model\generator;

use Codeception\TestCase\Test;
use umicms\project\module\metatag\model\generator\Parser;

/**
 * Class ParserTest
 * @package tests\unit\metatag\model\generator
 */
class ParserTest extends Test
{
    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @dataProvider getDataForTestParser
     * @param string $template
     * @param array  $expectedParsedTemplate
     * @param string $pattern
     */
    public function testParser($template, $expectedParsedTemplate, $pattern)
    {
        $parsedTemplate = $this->parser->parse($template);
        $this->assertEquals($expectedParsedTemplate, $parsedTemplate);
        $this->assertTrue((bool) preg_match($pattern, $this->parser->randomize($parsedTemplate)));
    }

    /**
     * @dataProvider getDataForTestParseTokens
     * @param string $template
     * @param array  $expectedTokens
     */
    public function testParseTokens($template, $expectedTokens)
    {
        $this->assertEquals($expectedTokens, $this->parser->parseTokens($template));
    }

    /**
     * @return array
     */
    public function getDataForTestParseTokens()
    {
        return [
            [
                'Replace %token% and one more equals %token%',
                ['%token%']
            ],
            [
                'Replace %token% and one more equals %token%. Here is another %cool_token%',
                ['%token%', '%cool_token%']
            ],
            [
                'empty string tokens: %% and % %',
                ['%%', '% %']
            ]

        ];
    }

    /**
     * @return array
     */
    public function getDataForTestParser()
    {
        return [
            [
                '{Крутая|Отличная|Прекрасная} новость!',
                [
                    ['Крутая', 'Отличная', 'Прекрасная'],
                    ' новость!'
                ],
                '/^(Крутая|Отличная|Прекрасная) новость!$/'
            ],
            [
                '{Крутая|Отличная|Прекрасная|} новость!',
                [
                    ['Крутая', 'Отличная', 'Прекрасная', ''],
                    ' новость!'
                ],
                '/^(Крутая|Отличная|Прекрасная|) новость!$/'
            ],
            [
                '{Крутая|Отличная|Прекрасная|} новость! {Реализуй мечту|Достигни своей мечты}!',
                [
                    ['Крутая', 'Отличная', 'Прекрасная', ''],
                    ' новость! ',
                    ['Реализуй мечту', 'Достигни своей мечты'],
                    '!'
                ],
                '/^(Крутая|Отличная|Прекрасная|) новость! (Реализуй мечту|Достигни своей мечты)!$/'
            ],
            [
                '{Крутая|Отличная|Прекрасная {вложеный|еще вложеный}} новость!',
                [
                    ['Крутая', 'Отличная', 'Прекрасная вложеный', 'еще вложеный'],
                    '} новость!'
                ],
                '/^(Крутая|Отличная|Прекрасная вложеный|еще вложеный)\} новость!$/'
            ],
        ];
    }

    protected function _before()
    {
        $this->parser = new Parser();
    }

}