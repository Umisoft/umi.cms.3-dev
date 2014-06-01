<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\category\controller;

use umi\http\Response;
use umicms\hmvc\controller\BaseSecureController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер вывода RSS-ленты категории.
 */
class BlogCategoryRssController extends BaseSecureController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var BlogModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $api
     */
    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $urlCategory = $this->getRouteVar('url');

        $blogCategory = $this->api->category()->getByUri($urlCategory);

        $blogCategoryPosts = $this->api->getPostByCategory([$blogCategory]);

        $rssFeed = $this->api->getPostRssFeed(
            $blogCategory->displayName,
            $blogCategory->contents,
            $blogCategoryPosts
        );

        $response = $this->createResponse($rssFeed);
        $response->headers->set('content-type', 'application/rss+xml; charset=utf8');

        $response->setIsCompleted();

        return $response;
    }
}
 