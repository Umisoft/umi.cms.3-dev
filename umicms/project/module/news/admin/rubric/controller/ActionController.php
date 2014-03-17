<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\orm\object\IObject;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\api\controller\TCollectionFormAction;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер операций.
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
    protected function getQueryActions()
    {
        return ['settings', 'form'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModifyActions()
    {
        return ['move', 'trash', 'untrash', 'emptyTrash'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection($collectionName)
    {
        if ($collectionName != $this->api->rubric()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        return $this->api->rubric()->getCollection();
    }

    protected function actionMove()
    {
        $data = $this->getIncomingData();

        if (!isset($data['object'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot get object to move.');
        }

        $object = $this->api->rubric()->getById($data['object'][IObject::FIELD_IDENTIFY]);
        $object->setVersion($data['object'][IObject::FIELD_VERSION]);

        if (isset($data['branch'])) {
            $branch = $this->api->rubric()->getById($data['branch'][IObject::FIELD_IDENTIFY]);
            $branch->setVersion($data['branch'][IObject::FIELD_VERSION]);
        } else {
            $branch = null;
        }

        if (isset($data['sibling'])) {
            $previousSibling = $this->api->rubric()->getById($data['sibling'][IObject::FIELD_IDENTIFY]);
            $previousSibling->setVersion($data['sibling'][IObject::FIELD_VERSION]);
        } else {
            $previousSibling = null;
        }

        $this->api->rubric()->move($object, $branch, $previousSibling);

        return '';
    }

    /**
     * Удаляет объект в корзину
     * @return string
     */
    public function actionTrash()
    {
        $object = $this->api->rubric()
            ->getCollection()
            ->getById($this->getQueryVar('id'));
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
    public function actionUntrash()
    {
        $object = $this->api->rubric()
            ->getCollection()
            ->getById($this->getQueryVar('id'));
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
    public function actionEmptyTrash()
    {
        $this->api->rubric()
            ->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }
}
