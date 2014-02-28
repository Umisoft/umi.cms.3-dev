<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\controller;

use umicms\base\controller\BaseController;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\search\api\SearchApi;

/**
 * Контроллер запроса результатов поиска
 */
class SearchController extends BaseController
{

    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    /**
     * Внедряет API поиска
     * @param SearchApi $api
     */
    public function __construct(SearchApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $query = $this->getQueryVar('query');
        $resultSet = $this->api->search($query);
        return $this->createViewResponse(
            'search/results',
            [
                'result' => $resultSet,
                'query' => $query
            ]
        );
    }
}
