<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft\view\controller;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\SitePageController;

/**
 * Контроллер вывода черновика блога.
 */
class PageController extends SitePageController
{
    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;

    /**
     * Конструктор.
     * @param BlogModule $module
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
    }

    /**
     * Возвращает страницу для отображения.
     * @param string $uri
     * @throws ResourceAccessForbiddenException в случае, если доступ к черновику запрещён
     * @return BlogPost
     */
    public function getPage($uri)
    {
        $blogDraft = $this->module->post()->getDraftByUri($uri);

        if (!$this->isAllowed($blogDraft)) {
            throw new ResourceAccessForbiddenException(
                $blogDraft,
                $this->translate('Access denied')
            );
        }

        return $blogDraft;
    }
}
 