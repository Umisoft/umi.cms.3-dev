<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\controller;

use umi\http\Response;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\news\api\NewsModule;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер вывода RSS-ленты рубрики.
 */
class NewsRubricRssController extends BaseAccessRestrictedController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var NewsModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $api
     */
    public function __construct(NewsModule $api)
    {
        $this->api = $api;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $urlRubric = $this->getRouteVar('url');

        $newsRubric = $this->api->rubric()->getByUri($urlRubric);

        $newsRubricItems = $this->api->getNewsByRubrics([$newsRubric]);

        $rssFeed = $this->api->getNewsRssFeed(
            $newsRubric->displayName,
            $newsRubric->contents,
            $newsRubricItems
        );

        $response = $this->createResponse($rssFeed);
        $response->headers->set('content-type', 'application/rss+xml; charset=utf8');

        $response->setIsCompleted();

        return $response;
    }
}
 