<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin\category\controller;

use umicms\exception\RuntimeException;
use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\blog\api\BlogModule;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
{
    /**
     * @var BlogModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $api
     */
    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->category()->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->category()->select(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        if (!isset($data['slug'])) {
            throw new RuntimeException('Slug is unknown');
        }
        $slug = $data['slug'];
        unset($data['slug']);

        if (!isset($data['parent'])) {
            $parent = null;
        } else {
            $parent = $this->api->category()->getById($data['parent']);
            unset($data['parent']);
        }

        $object = $this->api->category()->add($slug, $parent);

        // TODO: forms
        if (isset($data['category'])) {
            $rubric = $this->api->category()->getById($data['category']);
            $data['category'] = $rubric;
        }
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
 