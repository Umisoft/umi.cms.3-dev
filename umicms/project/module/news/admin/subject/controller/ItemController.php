<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject\controller;

use umicms\project\admin\api\controller\BaseRestItemController;
use umicms\orm\object\ICmsObject;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\news\object\NewsSubject;

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
    protected function get()
    {
        $id = $this->getRouteVar('id');
        return $this->api->subject()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function update(ICmsObject $object, array $data)
    {
        // TODO: forms
        foreach ($data as $propertyName => $value) {
            if ($object->hasProperty($propertyName)
                && !$object->getProperty($propertyName)->getIsReadOnly()
                && !is_array($value)

            ) {
                $object->setValue($propertyName, $value);
            }
        }

        $this->getObjectPersister()->commit();

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ICmsObject $object)
    {
        if ($object instanceof NewsSubject) {
            $this->api->subject()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }
}
 