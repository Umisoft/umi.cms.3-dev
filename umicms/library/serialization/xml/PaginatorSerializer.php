<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml;

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

        $this->getXmlWriter()->writeAttribute('pagesCount', $paginator->getPagesCount());

        if (!$paginator->getPagesCount()) {
            return;
        }

        $this->buildPageNode('firstPage', $paginator->getFirstPage(), $paginator->getFirstPageUrl());
        $this->buildPageNode('lastPage', $paginator->getLastPage(), $paginator->getLastPageUrl());

        if ($previous = $paginator->getPreviousPage()) {
            $this->buildPageNode('previousPage', $paginator->getPreviousPage(), $paginator->getPreviousPageUrl());
        }

        $this->buildPageNode('currentPage', $paginator->getCurrentPage(), $paginator->getCurrentPageUrl());

        if ($next = $paginator->getNextPage()) {
            $this->buildPageNode('nextPage', $paginator->getNextPage(), $paginator->getNextPageUrl());
        }

        $this->getXmlWriter()->startElement('range');
            foreach($paginator->getRangeUrls() as $page => $url) {
                $this->buildPageNode('page', $page, $url);
            }

        $this->getXmlWriter()->endElement();

    }


    protected function buildPageNode($nodeName, $number, $url)
    {
        $this->getXmlWriter()->startElement($nodeName);
        $this->getXmlWriter()->writeAttribute('number', $number);
        $this->getXmlWriter()->writeAttribute('url', $url);

        if ($number == $this->paginator->getCurrentPage()) {
            $this->getXmlWriter()->writeAttribute('current', true);
        }

        if ($number == $this->paginator->getLastPage()) {
            $this->getXmlWriter()->writeAttribute('last', true);
        }

        if ($number == $this->paginator->getFirstPage()) {
            $this->getXmlWriter()->writeAttribute('first', true);
        }

        $this->getXmlWriter()->endElement();
    }
}
 