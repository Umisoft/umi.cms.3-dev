<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\controller;

use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\DefaultPageController;

/**
 * Контроллер вывода поста блога, требующего модерации.
 */
class PostPageController extends DefaultPageController
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
        return $this->api->post()->getNeedModeratePostByUri($uri);
    }
}
 