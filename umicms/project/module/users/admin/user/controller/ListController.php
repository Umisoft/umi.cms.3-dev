<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin\user\controller;

use umicms\project\admin\api\controller\DefaultRestListController;
use umicms\project\module\users\api\UsersModule;

/**
 * Контроллер действий над списком.
 */
class ListController extends DefaultRestListController
{

    /**
     * @var UsersModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param UsersModule $api
     */
    public function __construct(UsersModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->user()->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->user()->select();
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        //TODO
    }
}
 