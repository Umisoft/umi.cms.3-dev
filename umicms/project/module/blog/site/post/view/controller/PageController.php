<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\view\controller;

use umicms\exception\InvalidArgumentException;
use umicms\orm\object\ICmsPage;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\SitePageController;

/**
 * Контроллер для отображения поста
 */
class PageController extends SitePageController
{
    /**
     * Заполняет стек хлебных крошек для текущего поста
     * @param ICmsPage $page
     * @throws InvalidArgumentException в случае, если не удалось получить хлебные крошки
     */
    protected function populateBreadcrumbsStack(ICmsPage $page)
    {
        if (!$page instanceof BlogPost) {
            throw new InvalidArgumentException($this->translate(
                'Cannot get navigation ancestry. Page should be instance of BlogPost.'
            ));
        }

        if (!is_null($page->category)) {
            /** @var ICmsPage[] $breadcrumbs */
            $breadcrumbs = $page->category->getAncestry()->result()->fetchAll();
            foreach ($breadcrumbs as $breadcrumb) {
                $this->pushPageToBreadcrumbs($breadcrumb);
            }
            $this->pushPageToBreadcrumbs($page->category);
        }
    }
}
