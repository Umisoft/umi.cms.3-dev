<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\item\controller;

use umicms\hmvc\controller\BaseController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер отображения новости.
 */
class NewsItemController extends BaseController
{

    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    public function __construct(NewsPublicApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $slug = $this->getRouteVar('slug');
        $newsItem = $this->api->news()->getBySlug($slug);

        return $this->createViewResponse(
            'view',
            [
                'newsItem' => $newsItem
            ]
        );
    }
}
 