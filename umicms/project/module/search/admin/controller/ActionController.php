<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\admin\controller;

use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\api\controller\TCollectionFormAction;
use umicms\project\module\search\api\SearchApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{
    use TCollectionFormAction;

    /**
     * @var SearchApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param SearchApi $api
     */
    public function __construct(SearchApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['search'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

    /**
     * @return Response
     */
    public function actionSearch()
    {
        $query = $this->getQueryVar('query');

        $resultSet = [];
        if (!is_null($query)) {
            $resultSet = $this->api->search($query);
        }
        return $resultSet;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection($collectionName)
    {
        return $this->api->getSearchIndexCollection();
    }
}
