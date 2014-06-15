<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\item\controller;

use umi\http\Response;
use umicms\hmvc\controller\BaseCmsController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\news\model\NewsModule;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер вывода общей RSS-ленты.
 */
class NewsItemRssController extends BaseCmsController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var NewsModule $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param NewsModule $module
     */
    public function __construct(NewsModule $module)
    {
        $this->module = $module;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $newsItems = $this->module->getNews();

        $rssFeed = $this->module->getNewsRssFeed(
            $this->getSiteDefaultTitle(),
            $this->getSiteDefaultDescription(),
            $newsItems
        );

        $response = $this->createResponse($rssFeed);
        $response->headers->set('content-type', 'application/rss+xml; charset=utf8');

        $response->setIsCompleted();

        return $response;
    }
}
 