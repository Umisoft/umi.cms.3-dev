<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\site\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\search\api\SearchApi;

/**
 * Контроллер запроса и вывода результатов поиска
 */
class SearchController extends SitePageController
{

    /**
     * API модуля "Поиск"
     * @var SearchApi $api
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

        $resultSet = null;
        if (!is_null($query)) {
            $resultSet = $this->api->search($query);
        }
        return $this->createViewResponse(
            'search/results',
            [
                'results' => $resultSet,
                'query' => $query
            ]
        );
    }
}
