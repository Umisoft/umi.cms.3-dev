<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\orm\object\IObject;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер операций.
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
        return ['move', 'trash', 'untrash', 'emptyTrash'];
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');

        if ($collectionName != $this->api->rubric()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->rubric()->getCollection()->getForm($typeName, $formName);
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
    protected function actionTrash()
    {
        $object = $this->api->rubric()
            ->getById($this->getQueryVar('id'));
        $this->api->rubric()
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
        $object = $this->api->rubric()
            ->getById($this->getQueryVar('id'));
        $this->api->rubric()
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
        $this->api->rubric()
            ->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }
}
