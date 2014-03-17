<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umicms\exception\RuntimeException;
use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseRestListController
{

    /**
     * @var NewsApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $api
     */
    public function __construct(NewsApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->rubric()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->api->rubric()->select();
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
            $parent = $this->api->rubric()->getById($data['parent']);
            unset($data['parent']);
        }

        $object = $this->api->rubric()->add($slug, $parent);

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
 