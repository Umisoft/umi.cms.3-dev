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
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\PageCollection;
use umicms\orm\collection\PageHierarchicCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\module\service\api\object\Backup;

/**
 * Контроллер действий над объектом.
 */
class DefaultRestActionController extends BaseDefaultRestController
{

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $action = $this->getRouteVar('action');

        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $this->checkSupportedAction($action, $this->getComponent()->getQueryActions());
                return $this->callAction($action);
            }
            case 'PUT': {

            }
            case 'POST': {
                $this->checkSupportedAction($action, $this->getComponent()->getModifyActions());
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
     * Возвращает форму для редактирования объекта коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionGetEditForm()
    {
        $typeName = $this->getRequiredQueryVar('type');

        return $this->getCollection()->getForm(ICmsCollection::FORM_EDIT, $typeName);
    }

    /**
     * Возвращает форму для создания объекта коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionGetCreateForm()
    {
        $typeName = $this->getRequiredQueryVar('type');

        return $this->getCollection()->getForm(ICmsCollection::FORM_CREATE, $typeName);
    }

    /**
     * Изменяет последнюю часть ЧПУ страницы.
     * @throws RuntimeException если невозможно изменить
     * @throws HttpException если пришли неверные данные
     * @return ICmsPage
     */
    protected function changeSlug()
    {

        $data = $this->getIncomingData();
        $object = $this->getEditedObject($data);

        if (isset($data[ICmsPage::FIELD_PAGE_SLUG])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change object slug. Slug is required.')
            );
        }

        if (!$object instanceof ICmsPage) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot change object slug. Object should be instance of "{class}".',
                    ['class' => 'umicms\orm\object\ICmsPage']
                )
            );
        }

        $collection = $this->getCollection();

        /**
         * @var CmsHierarchicObject|ICmsPage $object
         */
        if ($collection instanceof PageHierarchicCollection) {
            $collection->changeSlug($object, $data[ICmsPage::FIELD_PAGE_SLUG]);
        } elseif ($collection instanceof PageCollection) {
            $collection->changeSlug($object, $data[ICmsPage::FIELD_PAGE_SLUG]);
        } else {
            throw new RuntimeException(
                $this->translate(
                    'Cannot change object slug. Collection "{collection}" should be either instance of PageHierarchicCollection or instance of PageCollection.',
                    [
                        'collection' => $collection->getName()
                    ]
                )
            );
        }

        return $object;
    }

    /**
     * Изменяет активность объекта.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionActivate()
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
         $collection->activate($object);

        $this->getObjectPersister()->commit();

        return '';
    }

    /**
     * Изменяет активность объекта.
     * @throws RuntimeException если невозможно выполнить действие
     * @return ICmsObject
     */
    protected function actionDeactivate()
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
        $collection->deactivate($object);

        $this->getObjectPersister()->commit();

        return '';
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
    protected function actionGetBackupList()
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
    protected function actionGetBackup()
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
    protected function getEditedObject(array $data)
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
            throw new HttpNotFound('Action is not supported.');
        }
    }

}
