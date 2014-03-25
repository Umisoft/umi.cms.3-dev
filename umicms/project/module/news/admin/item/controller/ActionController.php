<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
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
        if ($collectionName != $this->api->news()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        return $this->api->news()->getCollection();
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');
        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->getCollection($collectionName)->getForm($typeName, $formName);
    }

    /**
     * Удаляет объект в корзину
     * @return string
     */
    protected function actionTrash()
    {
        $object = $this->api->news()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->news()
            ->trash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Восстанавливает объект из корзины
     * @return string
     */
    protected function actionUntrash()
    {
        $object = $this->api->news()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->news()
            ->untrash($object);
        $this->getObjectPersister()
            ->commit();

        return '';
    }

    /**
     * Очищает корзину
     * @return string
     */
    protected function actionEmptyTrash()
    {
        $this->api->news()
            ->emptyTrash();
        $this->getObjectPersister()
            ->commit();

        return '';
    }
}
