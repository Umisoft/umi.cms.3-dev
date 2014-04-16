<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\layout\controller;

use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\controller\DefaultRestItemController;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\module\structure\api\object\Layout;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends DefaultRestItemController
{
    /**
     * @var StructureModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param StructureModule $api
     */
    public function __construct(StructureModule $api)
    {
        $this->api = $api;
    }


    /**
     * {@inheritdoc}
     */
    protected function getRequestedObject()
    {
        $id = $this->getRouteVar('id');
        return $this->api->layout()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ICmsObject $object)
    {
        if ($object instanceof Layout) {
            $this->api->layout()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }
}
