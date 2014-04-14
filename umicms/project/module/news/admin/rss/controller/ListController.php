<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rss\controller;

use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
{
    /**
     * @var NewsApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $api
     */
    public function __construct(NewsApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->rss()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->rss()->select();
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {

    }
}
 