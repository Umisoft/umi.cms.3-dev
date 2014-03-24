<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin\user\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpUnauthorized;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\api\controller\TCollectionFormAction;
use umicms\project\module\users\api\UsersApi;

/**
 * Контроллер операций.
 */
class ActionController extends BaseRestActionController
{

    use TCollectionFormAction;

    /**
     * @var UsersApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param UsersApi $api
     */
    public function __construct(UsersApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['form'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return ['trash', 'untrash', 'emptyTrash'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection($collectionName)
    {
        if ($collectionName != $this->api->user()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        return $this->api->user()->getCollection();
    }

    /**
     * Удаляет объект в корзину
     * @return string
     */
    public function actionTrash()
    {
        $object = $this->api->user()
            ->getById($this->getQueryVar('id'));
        $this->api->trash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Восстанавливает объект из корзины
     * @return string
     */
    public function actionUntrash()
    {
        $object = $this->api->user()
            ->getById($this->getQueryVar('id'));
        $this->api->untrash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Очищает корзину
     * @return string
     */
    public function actionEmptyTrash()
    {
        $this->api->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }
}
