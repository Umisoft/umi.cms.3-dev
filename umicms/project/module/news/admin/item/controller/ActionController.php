<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsItem;
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

        if ($collectionName != $this->api->news()->getName()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->news()->getForm($typeName, $formName);
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

    /**
     * Возвращает список резервных копий.
     * @return Backup[]
     */
    protected function actionBackups()
    {
        $newsItemId = $this->getRequiredQueryVar('id');

        return $this->api->news()->getBackupList(
            $this->api->news()->getById($newsItemId)
        );
    }

    /**
     * Возвращает резервную копию.
     * @return NewsItem
     */
    protected function actionBackup()
    {
        $newsItemId = $this->getRequiredQueryVar('id');
        $backupId = $this->getRequiredQueryVar('backupId');
        $newsItem = $this->api->news()->getById($newsItemId);

        return $this->api->news()->getBackup($newsItem, $backupId);
    }
}
