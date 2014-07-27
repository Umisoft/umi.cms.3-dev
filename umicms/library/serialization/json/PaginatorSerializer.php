<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use umicms\pagination\CmsPaginator;

/**
 * Сериализатор для постраничной навигации.
 */
class PaginatorSerializer extends BaseSerializer
{
    /**
     * @var CmsPaginator $paginator
     */
    protected $paginator;
    /**
     * Сериализует CmsObject в XML.
     * @param CmsPaginator $paginator
     * @param array $options опции сериализации
     */
    public function __invoke(CmsPaginator $paginator, array $options = [])
    {
        $this->paginator = $paginator;

        $pagesCount = $paginator->getPagesCount();
        $result = [
            'pagesCount' => $pagesCount,
            'pagesCountInRage' => $paginator->getPagesCountInRange(),
            'itemsCount' => $paginator->getItemsCount()
        ];

        if ($pagesCount) {

            $result['firstPage'] = $this->buildPageInfo($paginator->getFirstPage(), $paginator->getFirstPageUrl());
            $result['lastPage'] = $this->buildPageInfo($paginator->getLastPage(), $paginator->getLastPageUrl());
            $result['currentPage'] = $this->buildPageInfo($paginator->getCurrentPage(), $paginator->getCurrentPageUrl());

            if ($previous = $paginator->getPreviousPage()) {
                $result['previousPage'] = $this->buildPageInfo($paginator->getPreviousPage(), $paginator->getPreviousPageUrl());
            }

            if ($next = $paginator->getNextPage()) {
                $result['nextPage'] = $this->buildPageInfo($paginator->getNextPage(), $paginator->getNextPageUrl());
            }

            $result['range'] = [];
            foreach($paginator->getRangeUrls() as $page => $url) {
                $result['range'][] = $this->buildPageInfo($page, $url);
            }
        }

        $this->delegate($result, $options);

    }


    protected function buildPageInfo($number, $url)
    {
        $result = [
            'number' => $number,
            'url' => $url
        ];

        if ($number == $this->paginator->getCurrentPage()) {
            $result['current'] = true;
        }

        if ($number == $this->paginator->getLastPage()) {
            $result['last'] = true;
        }

        if ($number == $this->paginator->getFirstPage()) {
            $result['first'] = true;
        }

        return $result;
    }
}
 