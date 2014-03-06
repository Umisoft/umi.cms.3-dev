<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер отображения новостной рубрики
 */
class RubricController extends SitePageController
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
        $url = $this->getRouteVar('url');
        $rubric = $this->api->rubric()->getByUrl($url);

        $this->pushCurrentPage($rubric);

        return $this->createViewResponse(
            'view',
            [
                'rubric' => $rubric
            ]
        );
    }
}
 