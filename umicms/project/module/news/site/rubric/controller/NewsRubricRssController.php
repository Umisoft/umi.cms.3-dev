<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\rubric\controller;

use umi\http\Response;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\news\model\NewsModule;
use umicms\project\IProjectSettingsAware;
use umicms\project\TProjectSettingsAware;

/**
 * Контроллер вывода RSS-ленты рубрики.
 */
class NewsRubricRssController extends BaseCmsController implements IProjectSettingsAware
{
    use TProjectSettingsAware;

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
        $urlRubric = $this->getRouteVar('url');

        $newsRubric = $this->module->rubric()->getByUri($urlRubric);

        $newsRubricItems = $this->module->getNewsByRubrics([$newsRubric]);

        $rssFeed = $this->module->getNewsRssFeed(
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
 