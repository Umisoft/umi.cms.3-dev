<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\admin\test\controller;

use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\controller\DefaultRestItemController;
use umicms\project\module\testmodule\api\object\TestObject;
use umicms\project\module\testmodule\api\TestModule;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends DefaultRestItemController
{
    /**
     * @var TestModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param TestModule $api
     */
    public function __construct(TestModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequestedObject()
    {
        $id = $this->getRouteVar('id');
        return $this->api->test()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ICmsObject $object)
    {
        if ($object instanceof TestObject) {
            $this->api->test()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }
}
