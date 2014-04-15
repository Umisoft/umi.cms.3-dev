<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\subject\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsSubject;
use umicms\project\module\service\api\object\Backup;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var NewsModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $api
     */
    public function __construct(NewsModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['form', 'backups', 'backup'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return ['trash','untrash','emptyTrash'];
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');

        if ($collectionName != $this->api->subject()->getName()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->subject()->getForm($typeName, $formName);
    }

    /**
     * Удаляет объект в корзину.
     * @return string
     */
    protected function actionTrash()
    {
        $object = $this->api->subject()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->subject()
            ->trash($object);
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
        $object = $this->api->subject()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->subject()
            ->untrash($object);
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
        $this->api->subject()
            ->emptyTrash();
        $this->getObjectPersister()
            ->commit();
        return '';
    }

    /**
     * Возвращает список резервных копий.
     * @return Backup[]
     */
    protected function actionBackups()
    {
        $newsSubjectId = $this->getRequiredQueryVar('id');

        return $this->api->subject()->getBackupList(
            $this->api->subject()->getById($newsSubjectId)
        );
    }

    /**
     * Возвращает резервную копию.
     * @return NewsSubject
     */
    protected function actionBackup()
    {
        $newsSubjectId = $this->getRequiredQueryVar('id');
        $backupId = $this->getRequiredQueryVar('backupId');
        $newsSubject = $this->api->subject()->getById($newsSubjectId);

        return $this->api->subject()->getBackup($newsSubject, $backupId);
    }
}
