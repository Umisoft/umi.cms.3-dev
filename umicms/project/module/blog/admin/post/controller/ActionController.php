<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin\post\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\service\api\object\Backup;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var BlogModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $api
     */
    public function __construct(BlogModule $api)
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

        if ($collectionName != $this->api->post()->getName()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->post()->getForm($typeName, $formName);
    }

    /**
     * Удаляет объект в корзину
     * @return string
     */
    protected function actionTrash()
    {
        $object = $this->api->post()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->post()
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
        $object = $this->api->post()
            ->getById($this->getRequiredQueryVar('id'));
        $this->api->post()
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
        $this->api->post()
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
        $blogPostId = $this->getRequiredQueryVar('id');

        return $this->api->post()->getBackupList(
            $this->api->post()->getById($blogPostId)
        );
    }

    /**
     * Возвращает резервную копию.
     * @return BlogPost
     */
    protected function actionBackup()
    {
        $blogPostId = $this->getRequiredQueryVar('id');
        $backupId = $this->getRequiredQueryVar('backupId');
        $blogPost = $this->api->post()->getById($blogPostId);

        return $this->api->post()->wakeUpBackup($blogPost, $backupId);
    }
}
