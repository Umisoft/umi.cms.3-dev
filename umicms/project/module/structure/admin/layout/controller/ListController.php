<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\layout\controller;

use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\structure\api\StructureApi;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
{

    /**
     * @var StructureApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param StructureApi $api
     */
    public function __construct(StructureApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->layout()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->layout()->select();
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        //TODO
    }
}
 