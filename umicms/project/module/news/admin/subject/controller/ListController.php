<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject\controller;

use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\news\api\NewsModule;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
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
    protected function getCollectionName()
    {
        return $this->api->subject()->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->subject()->select(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        $object = $this->api->news()->add();

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
}
 