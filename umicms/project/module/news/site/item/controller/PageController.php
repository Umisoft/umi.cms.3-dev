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
use umicms\project\module\news\model\object\NewsItem;
use umicms\hmvc\component\site\SitePageController;

/**
 * Контроллер для отображения новости.
 */
class PageController extends SitePageController
{
    /**
     * Заполняет стек хлебных крошек для текущей новости
     * @param ICmsPage $page
     * @throws InvalidArgumentException в случае, если не удалось получить хлебные крошки
     */
    protected function populateBreadcrumbsStack(ICmsPage $page)
    {
        if (!$page instanceof NewsItem) {
            throw new InvalidArgumentException($this->translate(
                'Cannot get navigation ancestry. Page should be instance of NewsItem.'
            ));
        }

        if (!is_null($page->rubric)) {
            /** @var ICmsPage[] $breadcrumbs */
            $breadcrumbs = $page->rubric->getAncestry()->result()->fetchAll();
            foreach ($breadcrumbs as $breadcrumb) {
                $this->pushPageToBreadcrumbs($breadcrumb);
            }
            $this->pushPageToBreadcrumbs($page->rubric);
        }
    }
}
