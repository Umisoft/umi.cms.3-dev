<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\item\controller;

use umicms\exception\InvalidArgumentException;
use umicms\orm\object\ICmsPage;
use umicms\project\module\news\api\object\NewsItem;
use umicms\project\site\controller\DefaultPageController;

/**
 * Контроллер для отображения новости.
 */
class PageController extends DefaultPageController
{
    /**
     * Возвращает хлебные крошки для текущего элемента.
     * @param ICmsPage $page
     * @throws InvalidArgumentException в случае, если не удалось получить хлебные крошки
     * @return NewsItem[]
     */
    protected function getNavigationAncestry(ICmsPage $page)
    {
        if (!$page instanceof NewsItem) {
            throw new InvalidArgumentException($this->translate(
                'Cannot get navigation ancestry. Page should be instance of NewsItem.'
            ));
        }

        $breadcrumbs = [];
        if (!is_null($page->rubric)) {
            $breadcrumbs = $page->rubric->getAncestry()->result()->fetchAll();
            $breadcrumbs[] = $page->rubric;
        }

        return $breadcrumbs;
    }
}
