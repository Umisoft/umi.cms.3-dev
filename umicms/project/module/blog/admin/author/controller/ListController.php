<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin\author\controller;

use umicms\project\admin\api\controller\DefaultRestListController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\users\api\UsersApi;

/**
 * Контроллер действий над списком.
 */
class ListController extends DefaultRestListController
{

    /**
     * @var BlogModule $api
     */
    protected $api;
    /**
     * @var UsersApi $user
     */
    protected $users;

    /**
     * Конструктор.
     * @param BlogModule $api
     * @param UsersApi $users
     */
    public function __construct(BlogModule $api, UsersApi $users)
    {
        $this->api = $api;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->api->author()->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return  $this->api->author()->select(false);
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        $object = $this->api->author()->add();

        // TODO: forms
        if (isset($data['profile'])) {
            $user = $this->users->user()->getById($data['profile']);
            $data['profile'] = $user;
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
 