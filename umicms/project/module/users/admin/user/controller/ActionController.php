<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\admin\user\controller;

use umi\hmvc\exception\http\HttpUnauthorized;
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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return ['login', 'logout', 'trash', 'untrash', 'emptyTrash'];
    }

    protected function actionLogin()
    {
        if (!$this->api->isAuthenticated()) {
            if (!$this->api->login($this->getPostVar('login'), $this->getPostVar('password'))) {
                throw new HttpUnauthorized(
                    $this->translate('Incorrect login or password.')
                );
            }
        }

        return $this->api->getCurrentUser();
    }

    protected function actionLogout()
    {
        $this->api->logout();

        return '';
    }

    /**
     * Удаляет объект в корзину
     * @return string
     */
    public function actionTrash()
    {
        $object = $this->api->getCollection()
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
        $object = $this->api->getCollection()
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
