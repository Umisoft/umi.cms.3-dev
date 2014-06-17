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

use umi\orm\objectset\IObjectSet;
use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\TActionController;
use umicms\project\module\search\model\SearchApi;

/**
 * Контроллер действий.
 */
class ActionController extends BaseController
{
    use TActionController;

    /**
     * @var SearchApi $module
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
     * Возвращает результат поиска.
     * @return IObjectSet
     */
    protected function actionSearch()
    {
        $query = $this->getQueryVar('query');

        $result = [];
        if (!is_null($query)) {
            $result = $this->api->search($query);
        }
        return $result;
    }
}
