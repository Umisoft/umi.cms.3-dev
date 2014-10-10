<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\model\highlight;

/**
 * Фрагментатор текста по найденным в нем словам.
 * Ограничивает найденные слова с обеих сторон по заданному количеству слов и собирает из них коллекцию фрагментов.
 * Поддерживает непосредственное итерирование по фрагментам:
 * <code>
 * foreach($fragmentatorInstance->fragmentize(3) as $fragment) {
 *     // операции с каждым фрагментом
 * }
 * </code>
 */
class Fragmenter implements \Iterator
{
    /**
     * @var Fragment[] $fragments текущая коллекция фрагментов
     */
    protected $fragments = [];
    /**
     * @var int $currentFragment указатель на текущий фрагмент в итерации
     */
    protected $currentFragment = 0;
    /**
     * @var array $foundMatches подстроки, найденные в тексте по регулярнму выражению
     */
    protected $foundMatches = [];

    /**
     * @var string $text текст, разбиваемый на фрагменты
     */
    private $text;

    /**
     * Конструктор фрагментатора.
     * @param string $text Текст, разбиваемый на фрагменты
     * @param string $keywordRegexp Регулярное выражение поиска по тексту
     */
    public function __construct($text, $keywordRegexp)
    {
        $this->text = $text;
        if (preg_match_all('/' . $keywordRegexp . '/ui', $text, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $pair) {
                $this->foundMatches[] = ['str' => $pair[0], 'pos' => $this->normalizeUtfMatchPos($text, $pair[1])];
            }
        }
    }


    /**
     * Приводит позицию подстроки из бинарной величины в уникодную
     * см. {@link https://bugs.php.net/bug.php?id=37391}
     * @param string $text
     * @param int $binaryPosition
     * @return int
     */
    private function normalizeUtfMatchPos($text, $binaryPosition)
    {
        return mb_strlen(substr($text, 0, $binaryPosition), 'utf-8');
    }

    /**
     * Разбивает текст на фрагменты по указанному лимиту слов с обеих сторон найденного слова.
     * Дожен быть вызван до первого использования коллекции фрагментов.
     * @param int $contextWordsLimit Сколько слов (максимум) может быть слева и справа от найденного
     * @return $this
     */
    public function fragmentize($contextWordsLimit)
    {
        $fragments = [];
        $textLength = mb_strlen($this->text, 'utf-8');

        $rightPosFrom = $leftPosTo = $rightPosTo = 0;
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
            $leftWords = preg_split(
                '/\s+/u',
                mb_substr($this->text, $leftPosFrom, $leftPosTo - $leftPosFrom, 'utf-8')
            );
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

        return $this;
    }

    /**
     * {@inheritdoc}
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
     * @param int $limit Максимальное число слов до и после ключевого выражения
     * @param Fragment[] $fragments Соседние фрагменты на склейку
     * @return Fragment[]
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
