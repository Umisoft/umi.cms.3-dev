<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umicms\hmvc\controller\BaseRestListController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
{

    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsPublicApi $api
     */
    public function __construct(NewsPublicApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->news()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return  $this->api->news()->select(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        // TODO: Implement create() method.
    }
}
 