<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umicms\base\controller\BaseRestItemController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends BaseRestItemController
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
    protected function get($guid)
    {
        return $this->api->news()->get($guid);
    }

    /**
     * {@inheritdoc}
     */
    protected function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * {@inheritdoc}
     */
    protected function delete()
    {
        // TODO: Implement delete() method.
    }
}
 