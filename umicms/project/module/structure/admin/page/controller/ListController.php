<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\page\controller;

use umi\orm\metadata\IObjectType;
use umicms\exception\RuntimeException;
use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\structure\api\StructureModule;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
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
    protected function getCollectionName()
    {
        return $this->api->element()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->element()->select();
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        // TODO: forms
        if (!isset($data['slug'])) {
            throw new RuntimeException('Slug is unknown');
        }
        $slug = $data['slug'];
        unset($data['slug']);

        if (!isset($data['parent'])) {
            $parent = null;
        } else {
            $parent = $this->api->element()->getById($data['parent']);
            unset($data['parent']);
        }

        $object = $this->api->element()->add($slug, IObjectType::BASE, $parent);

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
}
 