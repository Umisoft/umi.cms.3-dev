<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\highlight;

/**
 * Фрагмент поискового текста, состоящий из левой и правой частей и центральной, содержащей поисковое выражение.
 */
class Fragment
{
    /**
     * @var  $edgeLeft
     */
    private $edgeLeft;
    /**
     * @var  $edgeRight
     */
    private $edgeRight;
    /**
     * @var string $keyword
     */
    private $center;
    /**
     * @var  $startPos
     */
    private $startPos;
    /**
     * @var  $endPos
     */
    private $endPos;

    /**
     * @param $keyword
     * @param $wordsLeft
     * @param $wordsRight
     * @param $startPos
     * @param $endPos
     */
    public function __construct($keyword, $wordsLeft, $wordsRight, $startPos, $endPos)
    {
        $this->edgeLeft = $wordsLeft;
        $this->edgeRight = $wordsRight;
        $this->center = $keyword;
        $this->startPos = $startPos;
        $this->endPos = $endPos;
    }

    /**
     * Перекрывается ли фрагмент с другим
     * @param Fragment $fragmentNext
     * @param int $limit
     * @return bool
     */
    public function overlaps(Fragment $fragmentNext, $limit)
    {
        $cross = ($this->getEndPos() > $fragmentNext->getStartPos())
            && ($this->getStartPos() < $fragmentNext->getEndPos());
        if ($cross) {
            $edgePrev = $this->getEdgeRight();
            $edgeNext = $fragmentNext->getEdgeLeft();
            return count($this->intersectEdges($edgePrev, $edgeNext, $limit)) > 0;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getStartPos()
    {
        return $this->startPos;
    }

    /**
     * @return mixed
     */
    public function getEndPos()
    {
        return $this->endPos;
    }

    /**
     * @return mixed
     */
    public function getEdgeLeft()
    {
        return $this->edgeLeft;
    }

    /**
     * @return mixed
     */
    public function getEdgeRight()
    {
        return $this->edgeRight;
    }

    /**
     * @return string
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @param array $left
     * @param array $right
     * @param $limit
     * @return array
     */
    public function intersectEdges($left, $right, $limit)
    {
        $intersections = [];
        end($left);
        $lastLeftWord = current($left);
        reset($left);
        $intersectWidth = 0;
        foreach ($right as $r => $rightWord) {
            if ($lastLeftWord === $rightWord) {
                $intersectWidth = $r + 1;
            }
        }
        if (!$intersectWidth) {
            return [];
        }

        $maxIntersectWidth = min(count($left), count($right), $limit);
        do {
            $leftChunk = array_slice($left, -$intersectWidth, null, false);
            $rightChunk = array_slice($right, 0, $intersectWidth, false);
            $intersectWidth++;
            if ($leftChunk !== $rightChunk) {
                break;
            } else {
                $intersections = $leftChunk;
            }
        } while ($intersectWidth < $maxIntersectWidth);

        return $intersections;
    }

    /**
     * @param Fragment $next
     * @return Fragment
     */
    public function join(Fragment $next)
    {
        $balance = count($this->getEdgeRight()) - count($next->getEdgeLeft());
        $wordsMiddle = ($balance >= 0) ? $this->getEdgeRight() : $next->getEdgeLeft();
        $keywordList = array_merge([$this->getCenter()], $wordsMiddle, [$next->getCenter()]);
        return new Fragment(
            implode(' ', $keywordList),
            $this->getEdgeLeft(),
            $next->getEdgeRight(),
            $this->getStartPos(),
            $next->getEndPos()
        );
    }
}
