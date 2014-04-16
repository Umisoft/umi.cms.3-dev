<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\category\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\blog\api\BlogModule;

/**
 * Контроллер отображения категории блога.
 */
class BlogCategoryController extends SitePageController
{
    /**
     * @var BlogModule $api
     */
    protected $api;

    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $url = $this->getRouteVar('url');
        $category = $this->api->category()->getByUri($url);

        $this->pushCurrentPage($category);

        return $this->createViewResponse(
            'view',
            [
                'category' => $category
            ]
        );
    }
}
 