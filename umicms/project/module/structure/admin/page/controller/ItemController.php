<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\page\controller;

use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\IRecyclableObject;
use umicms\project\admin\api\controller\BaseRestItemController;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\StructureElement;

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
        return $this->api->element()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function update(ICmsObject $object, array $data)
    {
        if (!isset($data[StructureElement::FIELD_VERSION])) {
            throw new RuntimeException('Cannot save object. Object version is unknown');
        }

        $object->setVersion($data[StructureElement::FIELD_VERSION]);

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
        if ($object instanceof StructureElement) {
            $this->api->element()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }

    /**
     * @param IRecyclableObject $object
     */
    public function actionTrash(IRecyclableObject $object)
    {
        $this->api->element()->trash($object);
        $this->getObjectPersister()->commit();
    }

    /**
     * @param IRecyclableObject $object
     */
    public function actionUntrash(IRecyclableObject $object)
    {
        $this->api->element()->untrash($object);
        $this->getObjectPersister()->commit();
    }

    /**
     *
     */
    public function actionEmptyTrash()
    {
        $this->api->element()->emptyTrash();
    }
}
