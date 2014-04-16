<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umi\orm\collection\ISimpleHierarchicCollection;
use umicms\exception\RuntimeException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\PageCollection;
use umicms\orm\collection\PageHierarchicCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\project\module\service\api\object\Backup;

/**
 * Контроллер действий над объектом.
 */
class DefaultRestActionController extends BaseDefaultRestController
{
    /**
     * @var array $queryActions дополнительный список доступных действий на запрос данных
     */
    protected $queryActions = [];
    /**
     * @var array $modifyActions дополнительный список доступных действий на изменение данных
     */
    protected $modifyActions = [];

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $action = $this->getRouteVar('action');

        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $this->checkSupportedAction($action, $this->getQueryActions());
                return $this->callAction($action);
            }
            case 'PUT': {

            }
            case 'POST': {
                $this->checkSupportedAction($action, $this->getModifyActions());
                return $this->callAction($action);
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    'HTTP method is not allowed.',
                    ['GET', 'PUT', 'POST']
                );
            }
            default: {
                throw new HttpException(
                    Response::HTTP_NOT_IMPLEMENTED,
                    'HTTP method is not implemented.'
                );
            }
        }
    }

    /**
     * Возвращает список доступных действий на запрос данных.
     * @return array
     */
    public function getQueryActions()
    {
        $defaultActions = [
            'form'
        ];

        $collection = $this->getCollection();
        if ($collection instanceof IRecoverableCollection) {
            $defaultActions[] = 'backupList';
            $defaultActions[] = 'backup';
        }

        if ($collection instanceof PageCollection || $collection instanceof PageHierarchicCollection) {
            $defaultActions[] = 'viewOnSite';
        }


        return array_merge($defaultActions, $this->queryActions);
    }

    /**
     * Возвращает список доступных действий на изменение данных.
     * @return array
     */
    public function getModifyActions()
    {
        $defaultActions = [];
        $collection = $this->getCollection();

        if ($collection instanceof IActiveAccessibleCollection) {
            $defaultActions[] = 'switchActivity';
        }
        if ($collection instanceof SimpleHierarchicCollection) {
            $defaultActions[] = 'move';
        }
        if ($collection instanceof PageCollection || $collection instanceof PageHierarchicCollection) {
            $defaultActions[] = 'changeSlug';
        }
        if ($collection instanceof IRecyclableCollection) {
            $defaultActions[] = 'trash';
            $defaultActions[] = 'untrash';
        }


        return array_merge($defaultActions, $this->modifyActions);
    }

    /**
     * Вызывает действие.
     * @param string $action имя действия
     * @throws HttpForbidden если у текущего пользователя нет доступа к экшену.
     * @return Response
     */
    protected function callAction($action)
    {
        if (!$this->isAllowed($this, $action)) {
            throw new HttpForbidden(
                $this->translate(
                    'Cannot execute action "{action}" for component "{path}". Access denied.',
                    [
                        'action' => $action,
                        'path' => $this->getComponent()->getPath()
                    ]
                )
            );
        }

        $methodName = 'action' . ucfirst($action);
        $actionResult = $this->{$methodName}();

        if (!$actionResult) {
            return $this->createResponse('', Response::HTTP_NO_CONTENT);
        } else {
            return $this->createViewResponse(
                $action,
                [$action => $actionResult]
            );
        }
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->getCollection()->getForm($typeName, $formName);
    }

    /**
     * Изменяет активность объекта.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionSwitchActivity()
    {
        $collection = $this->getCollection();
        $object = $collection->getById($this->getRequiredQueryVar('id'));

        if (!$collection instanceof IActiveAccessibleCollection || !$object instanceof IActiveAccessibleObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot switch object activity. Collection "{collection}" and its objects should be active accessible.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IActiveAccessibleObject $object
         */
        if ($object->active) {
            $collection->deactivate($object);
        } else {
            $collection->activate($object);
        }

        $this->getObjectPersister()->commit();

        return $object;
    }

    /**
     * Удаляет объект в корзину.
     * @throws RuntimeException если невозможно выполнить действие
     * @return string
     */
    protected function actionTrash()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IRecyclableCollection || !$object instanceof IRecyclableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot trash object. Collection "{collection}" and its objects should be recyclable.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IRecyclableObject $object
         */
        $collection->trash($object);

        $this->getObjectPersister()->commit();

        return '';
    }

    /**
     * Восстанавливает объект из корзины.
     * @throws RuntimeException если невозможно выполнить действие
     * @return string
     */
    protected function actionUntrash()
    {
        $collection = $this->getCollection();
        $object = $this->getEditedObject($this->getIncomingData());

        if (!$collection instanceof IRecyclableCollection || !$object instanceof IRecyclableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot untrash object. Collection "{collection}" and its objects should be recyclable.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        /**
         * @var IRecyclableObject $object
         */
        $collection->trash($object);

        $collection->untrash($object);
        $this->getObjectPersister()->commit();

        return '';
    }

    /**
     * Возвращает список резервных копий.
     * @throws RuntimeException если невозможно выполнить действие
     * @return Backup[]
     */
    protected function actionBackupList()
    {
        $collection = $this->getCollection();
        $object = $collection->getById($this->getRequiredQueryVar('id'));

        if (!$collection instanceof IRecoverableCollection || !$object instanceof IRecoverableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get backup list for object. Collection "{collection}" and its objects should be recoverable.',
                    ['collection' => $collection->getName()]
                )
            );
        }
        /**
         * @var IRecoverableObject $object
         */
        return $collection->getBackupList($object);
    }

    /**
     * Возвращает резервную копию.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionBackup()
    {
        $collection = $this->getCollection();
        $object = $collection->getById($this->getRequiredQueryVar('id'));

        if (!$collection instanceof IRecoverableCollection || !$object instanceof IRecoverableObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get backup for object. Collection "{collection}" and its objects should be recoverable.',
                    ['collection' => $collection->getName()]
                )
            );
        }
        /**
         * @var IRecoverableObject $object
         */
        return $collection->wakeUpBackup($object, $this->getRequiredQueryVar('backupId'));
    }

    /**
     * Перемещает объекты.
     * @throws RuntimeException если невозможно выполнить действие
     * @throws HttpException если пришли невалидные данные
     * @return string
     */
    protected function actionMove()
    {
        $collection = $this->getCollection();

        if (!$collection instanceof ISimpleHierarchicCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot move objects. Collection "{collection}" should be hierarchic.',
                    ['collection' => $collection->getName()]
                )
            );
        }

        $data = $this->getIncomingData();

        if (!isset($data['object'])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot move objects. Objects info is undefined.')
            );
        }

        /**
         * @var CmsHierarchicObject $object
         */
        $object = $this->getEditedObject($data['object']);
        /**
         * @var CmsHierarchicObject $branch
         */
        $branch = isset($data['branch']) ? $this->getEditedObject($data['branch']) : null;
        /**
         * @var CmsHierarchicObject $previousSibling
         */
        $previousSibling = isset($data['sibling']) ? $this->getEditedObject($data['sibling']) : null;

        $collection->move($object, $branch, $previousSibling);

        return '';
    }

    /**
     * Возвращает объект для редактирования.
     * @param array $data данные объекта
     * @throws HttpException если данные невалидные.
     * @return ICmsObject
     */
    private function getEditedObject(array $data)
    {
        if (isset($data[ICmsObject::FIELD_IDENTIFY])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object id required.')
            );
        }

        if (isset($data[ICmsObject::FIELD_VERSION])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object version required.')
            );
        }

        $object = $this->getCollection()->getById($data[ICmsObject::FIELD_IDENTIFY]);
        $object->setVersion($data[ICmsObject::FIELD_VERSION]);

        return $object;
    }


    /**
     * Проверяет, поддерживается ли действие над объектом.
     * @param string $action имя действия
     * @param array $supportedActions список поддерживаемых действий
     * @throws HttpNotFound если действие не поддерживается
     */
    private function checkSupportedAction($action, array $supportedActions)
    {
        if (!in_array($action, $supportedActions)) {
            throw new HttpNotFound('Action not found.');
        }
    }

}
