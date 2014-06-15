<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\site\controller;

use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\search\model\SearchApi;

/**
 * Контроллер запроса и вывода результатов поиска
 */
class SearchController extends BaseSitePageController
{

    /**
     * модуль "Поиск"
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
