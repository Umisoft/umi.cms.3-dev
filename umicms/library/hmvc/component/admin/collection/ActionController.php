<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\collection;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\orm\collection\ISimpleHierarchicCollection;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\admin\TActionController;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\collection\CmsHierarchicPageCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\module\service\model\object\Backup;

/**
 * Контроллер действий над объектом.
 */
class ActionController extends BaseController
{
    use TActionController;

    /**
     * Возвращает форму для редактирования объекта коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionGetEditForm()
    {
        $typeName = $this->getRequiredQueryVar('type');

        return $this->getCollection()->getForm(ICmsCollection::FORM_EDIT, $typeName)->getView();
    }

    /**
     * Возвращает форму для создания объекта коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionGetCreateForm()
    {
        $typeName = $this->getRequiredQueryVar('type');

        return $this->getCollection()->getForm(ICmsCollection::FORM_CREATE, $typeName)->getView();
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
        if ($collection instanceof CmsHierarchicPageCollection) {
            $collection->changeSlug($object, $data[ICmsPage::FIELD_PAGE_SLUG]);
        } elseif ($collection instanceof CmsPageCollection) {
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

        $this->commit();

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

        $this->commit();

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

        $this->commit();

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
        $this->commit();

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
        $backupId = $this->getRequiredQueryVar('backupId');

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
        return $collection->wakeUpBackup($object, $backupId);
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
        if (!isset($data[ICmsObject::FIELD_IDENTIFY])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object id required.')
            );
        }

        if (!isset($data[ICmsObject::FIELD_VERSION])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot get object. Object version required.')
            );
        }

        $object = $this->getCollection()->getById($data[ICmsObject::FIELD_IDENTIFY]);
        $object->setVersion($data[ICmsObject::FIELD_VERSION]);

        return $object;
    }

}
