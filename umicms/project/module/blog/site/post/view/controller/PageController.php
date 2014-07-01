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
     * Возвращает хлебные крошки для текущего элемента.
     * @param ICmsPage $page
     * @throws InvalidArgumentException в случае, если не удалось получить хлебные крошки
     * @return BlogPost[]
     */
    protected function getNavigationAncestry(ICmsPage $page)
    {
        if (!$page instanceof BlogPost) {
            throw new InvalidArgumentException($this->translate(
                'Cannot get navigation ancestry. Page should be instance of BlogPost.'
            ));
        }

        $breadcrumbs = [];
        if (!is_null($page->category)) {
            $breadcrumbs = $page->category->getAncestry()->result()->fetchAll();
            $breadcrumbs[] = $page->category;
        }

        return $breadcrumbs;
    }
}
