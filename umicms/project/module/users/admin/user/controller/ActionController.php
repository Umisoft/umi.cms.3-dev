<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin\user\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\users\api\UsersApi;

/**
 * Контроллер операций.
 */
class ActionController extends BaseRestActionController
{
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
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');

        if ($collectionName != $this->api->user()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->user()->getCollection()->getForm($typeName, $formName);
    }

    /**
     * Удаляет объект в корзину.
     * @return string
     */
    protected function actionTrash()
    {
        $object = $this->api->user()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->user()->trash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Восстанавливает объект из корзины.
     * @return string
     */
    protected function actionUntrash()
    {
        $object = $this->api->user()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->user()->untrash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Очищает корзину.
     * @return string
     */
    protected function actionEmptyTrash()
    {
        $this->api->user()->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }
}
