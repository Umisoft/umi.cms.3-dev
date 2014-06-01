<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\helper;

use umi\templating\helper\pagination\PaginationHelper as FrameworkPaginationHelper;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * {@inheritdoc}
 */
class PaginationHelper extends FrameworkPaginationHelper implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Формирует контекс со ссылками на получение страниц.
     * @param array $context контекст постраничной навигации
     * @param string $pageParamName имя параметра с номером страницы для формирования ссылок
     * @return array
     */
    public function buildLinksContext(array $context, $pageParamName)
    {
        $result = [
            'firstPageUrl'         => $this->getPageUrl($context['firstPage'], $pageParamName),
            'lastPageUrl'          => $this->getPageUrl($context['lastPage'], $pageParamName),
            'currentPageUrl'       => $this->getPageUrl($context['currentPage'], $pageParamName),
            'previousPageUrl'      => $context['previousPage'] ? $this->getPageUrl($context['previousPage'], $pageParamName) : null,
            'nextPageUrl'          => $context['nextPage'] ? $this->getPageUrl($context['nextPage'], $pageParamName) : null
        ];

        $pagesRangeUrl = [];

        foreach ($context['pagesRange'] as $page) {
            $pagesRangeUrl[$page] = $this->getPageUrl($page, $pageParamName);
        }

        return $result + ['pagesRange' => $pagesRangeUrl];

    }

    /**
     * Возвращает ссылку на прсмотр страницы в постраничной навигации.
     * @param int $page номер страницы
     * @param string $pageParamName имя параметра с номером страницы для формирования ссылок
     * @return string
     */
    protected function getPageUrl($page, $pageParamName)
    {
        if ($page == 1) {
            $page = null;
        }

        return $this->getUrlManager()->getCurrentUrlWithParam($pageParamName, $page);
    }

}
 