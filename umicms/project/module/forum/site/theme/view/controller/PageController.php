<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\theme\view\controller;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\component\site\SitePageController;
use umicms\orm\object\ICmsPage;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * Контроллер для отображения темы форума.
 */
class PageController extends SitePageController
{
    /**
     * Заполняет стек хлебных крошек для текущей темы
     * @param ICmsPage $page
     * @throws InvalidArgumentException в случае, если не удалось получить хлебные крошки
     */
    protected function populateBreadcrumbsStack(ICmsPage $page)
    {
        if (!$page instanceof ForumTheme) {
            throw new InvalidArgumentException($this->translate(
                    'Cannot get navigation ancestry. Page should be instance of ForumTheme.'
                ));
        }

        if (!is_null($page->conference)) {
            $this->pushPageToBreadcrumbs($page->conference);
        }
    }
}
 