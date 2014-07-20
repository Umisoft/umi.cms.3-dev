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
                return $this->buildSlidingPagesRange($this->getPagesCountInRange());
            }
            case 'elastic': {
                return $this->buildElasticPagesRange($this->getPagesCountInRange());
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
    protected function buildSlidingPagesRange($pagesCountInRange)
    {
        $pagesCount = $this->getPagesCount();
        if ($pagesCountInRange >= $pagesCount) {
            return range(1, $pagesCount);
        }

        $currentPage = $this->getCurrentPage();

        if ($pagesCountInRange > $pagesCount) {
            return range(1, $pagesCount);
        } elseif ($currentPage + $pagesCountInRange <= $pagesCount) {
            return range($currentPage, $currentPage + $pagesCountInRange - 1);
        }

        return range ($pagesCount - $pagesCountInRange + 1, $pagesCount);
    }

    /**
     * Возвращает массив страниц для отображения в ряду - текущую и окружающие ее.
     * @param int $pagesCountInRange количество страниц отображаемых в ряду
     * @return array
     */
    protected function buildElasticPagesRange($pagesCountInRange)
    {
        $pagesCount = $this->getPagesCount();
        if ($pagesCountInRange >= $pagesCount) {
            return range(1, $pagesCount);
        }

        $currentPage = $this->getCurrentPage();
        $minOffset = ceil($pagesCountInRange / 2);
        $leftOffset = $rightOffset = $minOffset - 1;

        if ($currentPage - $leftOffset <= 0) {
            $leftOffset = $currentPage - 1;
            $rightOffset = $pagesCountInRange - $currentPage;
        }

        if ($pagesCount - $currentPage < $minOffset) {
            $leftOffset = $currentPage - $pagesCount + $pagesCountInRange - 1;
            $rightOffset = $pagesCount - $currentPage;
        }

        return range($currentPage - $leftOffset, $currentPage + $rightOffset);
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
 