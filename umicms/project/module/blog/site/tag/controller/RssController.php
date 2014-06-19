<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\tag\controller;

use umi\http\Response;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер вывода общей RSS-ленты.
 */
class RssController extends BaseCmsController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var BlogModule $module
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
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $slugTag = $this->getRouteVar('slug');

        $blogTag = $this->module->tag()->getByUri($slugTag);

        $BlogTagPosts = $this->module->getPostByTag([$blogTag]);

        $rssFeed = $this->module->getPostRssFeed(
            $blogTag->displayName,
            $blogTag->contents,
            $BlogTagPosts
        );

        $response = $this->createResponse($rssFeed);
        $response->headers->set('content-type', 'application/rss+xml; charset=utf8');

        $response->setIsCompleted();

        return $response;
    }
}
 