<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\draft\controller;

use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\DefaultPageController;

/**
 * Контроллер вывода черновика блога.
 */
class BlogDraftPageController extends DefaultPageController
{
    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $blogModule
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
    }

    /**
     * Возвращает страницу для отображения.
     * @param string $uri
     * @return BlogPost
     */
    public function getPage($uri)
    {
        return $this->api->post()->getDraftByUri($uri);
    }
}
 