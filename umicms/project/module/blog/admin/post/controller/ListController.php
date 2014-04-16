<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin\post\controller;

use umicms\project\admin\api\controller\DefaultRestListController;
use umicms\project\module\blog\api\BlogModule;

/**
 * Контроллер действий над списком.
 */
class ListController extends DefaultRestListController
{

    /**
     * @var BlogModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $api
     */
    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->post()->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return  $this->api->post()->select(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {

    }
}
 