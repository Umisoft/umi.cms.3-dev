<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\controller\DefaultRestItemController;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsItem;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends DefaultRestItemController
{
    /**
     * @var NewsModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $api
     */
    public function __construct(NewsModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequestedObject()
    {
        $id = $this->getRouteVar('id');
        return $this->api->news()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ICmsObject $object)
    {
        if ($object instanceof NewsItem) {
            $this->api->news()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }
}
