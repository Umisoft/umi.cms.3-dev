<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\api\object\Backup;

/**
 * Коллекция резервных копий объектов.
 *
 * @method Backup get($guid, $withLocalization = false) Возвращает резервную копию по ее GUID.
 * @method Backup getById($objectId, $withLocalization = false) Возвращает резервную копию по ее id.
 * @method Backup add($typeName = IObjectType::BASE) Создает и возвращает резервную копию.
 */
class BackupCollection extends SimpleCollection
{

    /**
     * Возвращает селектор для выбора резервных копий.
     * @return CmsSelector|Backup[]
     */
    public function select()
    {
        return parent::select()
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
     * @param IRecoverableObject $object
     * @return CmsSelector|Backup[]
     */
    public function getList(IRecoverableObject $object)
    {
        return $this->select()
            ->where(Backup::FIELD_OBJECT_ID)->equals($object->getId())
            ->where(Backup::FIELD_COLLECTION_NAME)->equals($object->getCollectionName())
            ->orderBy(Backup::FIELD_DATE, CmsSelector::ORDER_DESC);
    }

    /**
     * Восстанавливает в памяти резервную копию объекта.
     * @param IRecoverableObject $object
     * @param int $backupId идентификатор резервной копии
     * @throws RuntimeException если не удалось восстановить объект
     * @throws InvalidArgumentException если резервная копия не принадлежит указанному объекту
     * @return IRecoverableObject
     */
    public function wakeUpBackup(IRecoverableObject $object, $backupId)
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

        if (!$restoredObject instanceof IRecoverableObject) {
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
     * @param IRecoverableObject $object
     * @return $this
     */
    public function createBackup(IRecoverableObject $object)
    {
        /** @var Backup $backup */
        $backup = $this->add();
        $backup->objectId = $object->getId();
        $backup->collectionName = $object->getCollectionName();
        $backup->date = new \DateTime();
        $backup->user = $object->editor;
        $backup->getProperty(Backup::FIELD_DATA)->setValue(serialize($object));

        return $this;
    }
}
 