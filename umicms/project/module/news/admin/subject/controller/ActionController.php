<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject\controller;

use HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\api\controller\TCollectionFormAction;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{

    use TCollectionFormAction;

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
    public function getQueryActions()
    {
        return ['settings', 'form'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return ['trash','untrash','emptyTrash'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection($collectionName)
    {
        if ($collectionName != $this->api->subject()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        return $this->api->subject()->getCollection();
    }


    /**
     * Удаляет объект в корзину
     * @return string
     */
    public function actionTrash()
    {
        $object = $this->api->subject()
            ->getById($this->getQueryVar('id'));
        $this->api->subject()
            ->trash($object);
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
        $object = $this->api->subject()
            ->getById($this->getQueryVar('id'));
        $this->api->subject()
            ->untrash($object);
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
        $this->api->subject()
            ->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }
}
