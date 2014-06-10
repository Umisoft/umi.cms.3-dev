<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\admin\controller;

use umi\http\Response;
use umicms\project\admin\rest\controller\DefaultRestActionController;
use umicms\project\module\search\api\SearchApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends DefaultRestActionController
{
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
    protected function actionSearch()
    {
        $query = $this->getQueryVar('query');

        $resultSet = [];
        if (!is_null($query)) {
            $resultSet = $this->api->search($query);
        }
        return $resultSet;
    }
}
