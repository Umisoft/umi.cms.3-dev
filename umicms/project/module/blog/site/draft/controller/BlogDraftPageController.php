<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft\controller;

use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\site\controller\DefaultPageController;

/**
 * Контроллер вывода черновика блога.
 */
class BlogDraftPageController extends DefaultPageController
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
     * @return BlogPost
     */
    public function getPage($uri)
    {
        return $this->module->post()->getDraftByUri($uri);
    }
}
 