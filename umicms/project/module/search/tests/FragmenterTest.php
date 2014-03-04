<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\tests;

use umicms\project\module\search\highlight\Fragment;
use umicms\project\module\search\highlight\Fragmenter;

/**
 * Class FragmenterTest
 */
class FragmenterTest extends SearchTestCase
{
    public function testOverlaps()
    {
        $frag1 = new Fragment('foo', ['f', 'o', 'o',], ['a', 'b', 'c',], 0, 15);
        $frag2 = new Fragment('foo', ['b', 'c', 'd',], ['b', 'a', 'r',], 12, 27);
        $this->assertTrue($frag1->overlaps($frag2, 3));
        $this->assertFalse($frag2->overlaps($frag1, 3));
    }

    public function testRejoinFragments()
    {
        $fr = new Fragmenter('a X c d X e f', 'X');
        $fragmentsMap = [];
        /** @var $frag Fragment */
        foreach ($fr->fragmentize(3) as $frag) {
            $fragmentsMap[] = [$frag->getEdgeLeft(), $frag->getCenter(), $frag->getEdgeRight()];
        }
        $mapExpected = [
            [['a'], 'X c d X', ['e','f']],
        ];
        $this->assertEquals($mapExpected, $fragmentsMap, "Expected correct overlapping");
    }
}
