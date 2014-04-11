<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject\controller;

use umi\http\Response;
use umi\rss\TRssFeedAware;
use umicms\hmvc\controller\BaseController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\news\api\NewsApi;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер вывода RSS-ленты сюжета.
 */
class NewsSubjectRssController extends BaseController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var NewsApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $api
     */
    public function __construct(NewsApi $api)
    {
        $this->api = $api;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $slugSubject = $this->getRouteVar('slug');

        $newsSubject = $this->api->subject()->getBySlug($slugSubject);

        $newsSubjectItems = $this->api->getSubjectNews([$newsSubject->guid]);

        $rssFeed = $this->api->getNewsRssFeed(
            $newsSubject->displayName,
            $newsSubject->contents,
            $newsSubjectItems
        );

        $response = $this->createResponse($rssFeed);
        $response->headers->set('content-type', 'application/rss+xml; charset=utf8');

        $response->setIsCompleted();

        return $response;
    }
}
 