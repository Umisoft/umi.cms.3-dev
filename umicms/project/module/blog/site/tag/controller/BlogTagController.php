<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\tag\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\blog\api\BlogModule;

/**
 * Контроллер отображения тэгов.
 */
class BlogTagController extends SitePageController
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
        $slug = $this->getRouteVar('slug');
        $blogTag = $this->api->tag()->getByUri($slug);

        $this->pushCurrentPage($blogTag);

        return $this->createViewResponse(
            'view',
            [
                'blogTag' => $blogTag
            ]
        );
    }
}
 