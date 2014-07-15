<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\pagination;

use umi\pagination\Paginator;
use umicms\exception\OutOfBoundsException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * {@inheritdoc}
 */
class CmsPaginator extends Paginator implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Тип постраничной навигации для вывода всех возможных страниц
     */
    const TYPE_ALL = 'all';
    /**
     * Тип постраничной навигации для вывода текущей страницы и указанного количества последующих страниц
     */
    const TYPE_SLIDING = 'sliding';
    /**
     * Тип "прыгающей" постраничной навигации для вывода
     */
    const TYPE_JUMPING = 'jumping';
    /**
     * Тип постраничной навигации для вывода текущей страницы и указанного количества страниц вокруг
     */
    const TYPE_ELASTIC = 'elastic';

    /**
     * @var string $type тип постраничной навигации
     */
    private $type;
    /**
     * @var int $pagesCount количество страниц в ряду
     */
    private $pagesCount;
    /**
     * @var string $pageParam имя GET-параметра, хранящего номер страницы
     */
    private $pageParam = 'p';

    /**
     * Возвращает ссылку на прсмотр страницы в постраничной навигации.
     * @param int $page номер страницы
     * @throws UnexpectedValueException если неизвестно, какой параметр использовать для построения ссылки
     * @return string
     */
    public function getPageUrl($page)
    {
        if (!$this->pageParam) {
            throw new UnexpectedValueException(
                $this->translate('Cannot build url. Page parameter is unknown')
            );
        }

        if ($page == 1) {
            $page = null;
        }

        return $this->getUrlManager()->getCurrentUrlWithParam($this->pageParam, $page);
    }

    /**
     * Устанавливает количество страниц в ряду.
     * @param int $pagesCount
     * @return $this
     */
    public function setPagesCount($pagesCount)
    {
        $this->pagesCount = (int) $pagesCount;

        return $this;
    }

    /**
     * Устанавливает тип постраничной навигации.
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Устанавливает имя GET-параметра, хранящего номер страницы.
     * @param $pageParam
     * @return $this
     */
    public function setPageParam($pageParam)
    {
        $this->pageParam = $pageParam;

        return $this;
    }

    /**
     * Возвращает номер первой страницы.
     * @return int
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * Возвращает номер последней страницы.
     * @return int
     */
    public function getLastPage()
    {
        return $this->getPagesCount();
    }

    /**
     * Возвращает номер предыдущей страницы, либо null, если текущая страница первая.
     * @return int|null
     */
    public function getPreviousPage()
    {
        return ($this->getCurrentPage() > 1) ? $this->getCurrentPage() - 1 : null;
    }

    /**
     * Возвращает номер следующей страницы, либо null, если текущая страница последняя.
     * @return int|null
     */
    public function getNextPage()
    {
        return ($this->getCurrentPage() < $this->getPagesCount()) ? $this->getCurrentPage() + 1 : null;
    }

    /**
     * Возвращает ссылку на первую страницу.
     * @return string
     */
    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }

    /**
     * Возвращает ссылку на последнюю страницу.
     * @return string
     */
    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getPagesCount());
    }

    /**
     * Возвращает ссылку на текущую страницу.
     * @return string
     */
    public function getCurrentPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage());
    }

    /**
     * Возвращает ссылку на предыдущую страницу, либо null, если текущая страница первая.
     * @return null|string
     */
    public function getPreviousPageUrl()
    {
        if ($previous = $this->getPreviousPage()) {
            return $this->getPageUrl($previous);
        }

        return null;
    }

    /**
     * Возвращает ссылку на следующую страницу, либо null, если текущая страница последняя.
     * @return null|string
     */
    public function getNextPageUrl()
    {
        if ($next = $this->getNextPage()) {
            return $this->getPageUrl($next);
        }

        return null;
    }

    /**
     * Возвращает список ссылок на страницы в ряду, ключами массива являются номера страниц.
     * @return array
     */
    public function getRangeUrls()
    {
        $pagesRangeUrl = [];

        foreach ($this->getPagesRage() as $page) {
            $pagesRangeUrl[$page] = $this->getPageUrl($page);
        }

        return $pagesRangeUrl;
    }

    /**
     * Возвращает список страниц для отображения в соответствии с заданным типом
     * @throws OutOfBoundsException если задан неизвестный тип
     * @return array
     */
    public function getPagesRage()
    {
        switch($this->type) {
            case 'all': {
                return range(1, $this->getPagesCount());
            }
            case 'sliding': {
                return $this->slidingBuildPagesRange($this->getPagesCountInRange());
            }
            case 'elastic': {
                return $this->elasticBuildPagesRange($this->getPagesCountInRange());
            }
            case 'jumping': {
                return $this->jumpingBuildPagesRange($this->getPagesCountInRange());
            }
            default: {
                throw new OutOfBoundsException(
                    $this->translate(
                        'Cannot get page range. Pagination type "{type}" is unknown.', ['type' => $this->type]
                    )
                );
            }
        }
    }

    /**
     * Возвращает массив страниц для отображения в ряду - текущую и следующие за ней
     * @param int $pagesCountInRange количество страниц отображаемых в ряду
     * @return array
     */
    protected function slidingBuildPagesRange($pagesCountInRange)
    {
        $currentPage = $this->getCurrentPage();
        $pagesCount = $this->getPagesCount();

        $rangeStart = $currentPage - ceil($pagesCountInRange / 2);
        $lastPossibleStart = $pagesCount - $pagesCountInRange + 1;
        if ($rangeStart <= 0) {
            $rangeEnd = $pagesCountInRange >= $pagesCount ? $pagesCount : $pagesCountInRange;

            return range(1, $rangeEnd);
        } elseif ($rangeStart >= $lastPossibleStart) {
            $lastPossibleStart = $lastPossibleStart ?: 1;

            return range($lastPossibleStart, $pagesCount);
        } else {
            return range($rangeStart + 1, $rangeStart + $pagesCountInRange);
        }
    }

    /**
     * Возвращает массив страниц для отображения в ряду - текущую и окружающие ее.
     * @param int $pagesCountInRange количество страниц отображаемых в ряду
     * @return array
     */
    protected function elasticBuildPagesRange($pagesCountInRange)
    {
        $currentPage = $this->getCurrentPage();
        $pagesCount = $this->getPagesCount();
        $minPagesCountInRange = ceil($pagesCountInRange / 2);
        if ($currentPage <= $minPagesCountInRange) {
            $pagesCountInRange = $currentPage + $minPagesCountInRange - 1;
        } elseif ($pagesCount - $currentPage - 1 <= $minPagesCountInRange) {
            $pagesCountInRange = $pagesCountInRange - $minPagesCountInRange + 1;
        }

        return $this->slidingBuildPagesRange($pagesCountInRange);
    }

    /**
     * Возвращает массив номеров страниц для отображения в ряду.
     * @param int $pagesCountInRange количество страниц отображаемых в ряду
     * @return array
     */
    protected function jumpingBuildPagesRange($pagesCountInRange)
    {
        $currentPage = $this->getCurrentPage();
        $pagesCount = $this->getPagesCount();
        $currentRange = ceil($currentPage / $pagesCountInRange);
        $ragesCount = ceil($pagesCount / $pagesCountInRange);
        if ($currentRange == 1) {
            $rangeEnd = $pagesCount < $pagesCountInRange ? $pagesCount : $pagesCountInRange;

            return range(1, $rangeEnd);
        }
        $rangeStart = ($currentRange - 1) * $pagesCountInRange + 1;
        if ($currentRange < $ragesCount) {
            return range($rangeStart, $rangeStart + $pagesCountInRange - 1);
        } else {
            return range($rangeStart, $pagesCount);
        }
    }

    /**
     * Возвращает количество страниц в ряду
     * @throws UnexpectedValueException
     * @return int
     */
    protected function getPagesCountInRange()
    {
        if ($this->pagesCount <= 0 || !is_int($this->pagesCount)) {
            throw new UnexpectedValueException(
                $this->translate(
                    '{count} is wrong pages count in range. Value should be positive integer.',
                    ['count' => $this->pagesCount]
                )
            );
        }

        return $this->pagesCount;
    }

}
 