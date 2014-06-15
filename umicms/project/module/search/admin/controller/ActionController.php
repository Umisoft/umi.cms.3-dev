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
use umicms\project\module\search\model\SearchApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends DefaultRestActionController
{
    /**
     * @var SearchApi $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param SearchApi $module
     */
    public function __construct(SearchApi $module)
    {
        $this->module = $module;
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
            $resultSet = $this->module->search($query);
        }
        return $resultSet;
    }
}
