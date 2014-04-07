<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\api;

use umi\orm\manager\IObjectManagerAware;
use umi\orm\manager\TObjectManagerAware;
use umicms\api\IPublicApi;
use umicms\api\repository\BaseObjectRepository;
use umicms\exception\InvalidArgumentException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\api\object\Backup;
use umicms\project\module\users\api\UsersApi;

/**
 * Репозиторий для работы с бэкапами.
 */
class BackupRepository extends BaseObjectRepository implements IPublicApi, IObjectManagerAware
{
    use TObjectManagerAware;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'serviceBackup';

    public $userApi;

    public function __construct(UsersApi $usersApi)
    {
        $this->userApi = $usersApi;
    }


    /**
     * Возвращает селектор для выбора бэкапов.
     * @return CmsSelector
     */
    public function select()
    {
        return $this->getCollection()->select()
            ->fields(
                [
                    Backup::FIELD_OBJECT_ID,
                    Backup::FIELD_COLLECTION_NAME,
                    Backup::FIELD_DATE,
                    Backup::FIELD_USER
                ]
            );
    }

    /**
     * Возвращает селектор списка бэкапов.
     * @param ICmsObject $object
     * @return CmsSelector
     */
    public function getList(ICmsObject $object)
    {
        return $this->select()
            ->where(Backup::FIELD_OBJECT_ID)->equals($object->getId())
            ->where(Backup::FIELD_COLLECTION_NAME)->equals($object->getCollectionName());
    }

    /**
     * Возвращает бэкап по GUID.
     * @param $guid
     * @throws NonexistentEntityException
     * @return Backup
     */
    public function getByGuid($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает резервную копию по Id.
     * @param int $id
     * @throws NonexistentEntityException
     * @return Backup
     */
    public function getById($id)
    {
        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }


    /**
     * Восстанавливает в памяти резервную копию объекта.
     * @param ICmsObject $object
     * @param int $backupId идентификатор резервной копии
     * @throws RuntimeException если не удалось восстановить объект
     * @throws InvalidArgumentException если резервная копия не принадлежит указанному объекту
     * @return ICmsObject
     */
    public function wakeUpBackup(ICmsObject $object, $backupId)
    {
        $backup = $this->getById($backupId);
        if ($backup->objectId != $object->getId()) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Invalid backup "{backupId}" for object "{guid}".',
                    [
                        'backupId' => $backupId,
                        'guid' => $object->getGUID()
                    ]
                )
            );
        }

        $restoredObject = $backup->data;

        if (!$restoredObject instanceof ICmsObject) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot restore backup "{backupId}" for object "{guid}". Backup corrupted.',
                    [
                        'backupId' => $backupId,
                        'guid' => $object->getGUID()
                    ]
                )
            );
        }

        $this->getObjectManager()->wakeUpObject($restoredObject);

        return $restoredObject;
    }

    /**
     * Создаёт резервную копию объекта.
     * @param ICmsObject $object
     * @return $this
     */
    public function createBackup(ICmsObject $object)
    {
        /** @var Backup $backup */
        $backup = $this->getCollection()->add();
        $backup->objectId = $object->getId();
        $backup->collectionName = $object->getCollectionName();
        $backup->date->setCurrent();
        $backup->user = $object->editor;
        $backup->getProperty(Backup::FIELD_DATA)->setValue(serialize($object));

        return $this;
    }
}
 