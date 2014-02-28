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
 * Class Fragmenter
 */
class Fragmenter implements \Iterator
{
    /**
     * @var Fragment[] $fragments
     */
    protected $fragments = [];
    /**
     * @var int $currentFragment
     */
    protected $currentFragment = 0;
    /**
     * @var array $foundMatches
     */
    protected $foundMatches = [];
    /**
     * @var string $text
     */
    private $text;
    /**
     * @var string $searchRegexp
     */
    private $searchRegexp;

    /**
     * @param string $text
     * @param $keywordRegexp
     * @throws \LogicException
     */
    public function __construct($text, $keywordRegexp)
    {
        $this->text = $text;
        preg_match_all('/' . $keywordRegexp . '/ui', $text, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);
        if (empty($matches[0])) {
            throw new \LogicException("Fragmenter expects to receive matchable expression");
        }
        foreach ($matches[0] as $pair) {
            $this->foundMatches[] = ['str' => $pair[0], 'pos' => $this->normalizeUtfMatchPos($text, $pair[1])];
        }
        $this->searchRegexp = $keywordRegexp;
    }


    /**
     * @param $keyword
     * @return mixed
     */
    public function highlightKeyword($keyword)
    {
        return $keyword;
    }

    /**
     * @param $text
     * @param $binaryPosition
     * @return int
     */
    private function normalizeUtfMatchPos($text, $binaryPosition)
    {
        return mb_strlen(substr($text, 0, $binaryPosition), 'utf-8');
    }

    /**
     * @param $contextWordsLimit
     */
    public function fragmentize($contextWordsLimit)
    {
        $fragments = [];
        $textLength = mb_strlen($this->text, 'utf-8');
        $leftPosFrom = $rightPosFrom = $leftPosTo = $rightPosTo = 0;
        foreach ($this->foundMatches as $i => $matchPair) {
            $word = $matchPair['str'];
            if ($i == 0) {
                $leftPosFrom = 0;
            } else {
                $leftPosFrom = $rightPosFrom;
            }
            $leftPosTo = $matchPair['pos'];
            $rightPosFrom = $leftPosTo + mb_strlen($word, 'utf-8');
            $rightPosTo = isset($this->foundMatches[$i + 1]) ? $this->foundMatches[$i + 1]['pos'] : $textLength;
            $leftWords = preg_split('/\s+/u', mb_substr($this->text, $leftPosFrom, $leftPosTo - $leftPosFrom, 'utf-8'));
            $rightWords = preg_split(
                '/\s+/u',
                mb_substr($this->text, $rightPosFrom, $rightPosTo - $rightPosFrom, 'utf-8')
            );
            $leftWords = array_slice(array_filter($leftWords), -$contextWordsLimit, $contextWordsLimit);
            $rightWords = array_slice(array_filter($rightWords), 0, $contextWordsLimit);
            $fragments[] = new Fragment($word, $leftWords, $rightWords, $leftPosFrom, $rightPosTo, $this);
        }
        $this->fragments = $this->rejoinFragments($contextWordsLimit, $fragments);

        $this->rewind();
    }

    /**
     * @return Fragment
     */
    public function current()
    {
        return $this->fragments[$this->currentFragment];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->currentFragment++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->currentFragment;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->fragments[$this->currentFragment]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->currentFragment = 0;
    }

    /**
     * Перегруппировывает фрагменты, «склеивая» пересекающиеся по краям в более крупные
     * @param $limit
     * @param $fragments
     * @return array
     */
    private function rejoinFragments($limit, $fragments)
    {
        $joinedFragments = [];
        $skip = 0;
        /** @var $frag Fragment */
        foreach ($fragments as $f => $frag) {
            if ($skip > 0) {
                $skip--;
                continue;
            }
            $next = isset($fragments[$f + 1]) ? $fragments[$f + 1] : null;
            if ($next && $frag->overlaps($next, $limit)) {
                $joinedFragments[] = $frag->join($next);
                $skip = 1;
            } else {
                $joinedFragments[] = $frag;
            }
        }
        return $joinedFragments;
    }

}
