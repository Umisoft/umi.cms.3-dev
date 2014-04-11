<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\layout\controller;

use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\controller\BaseRestItemController;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\api\object\Layout;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends BaseRestItemController
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
    protected function get()
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
