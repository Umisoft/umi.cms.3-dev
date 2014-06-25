<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\CmsCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\model\object\Backup;

/**
 * Коллекция резервных копий объектов.
 *
 * @method Backup get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает резервную копию по ее GUID.
 * @method Backup getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает резервную копию по ее id.
 * @method Backup add($typeName = IObjectType::BASE) Создает и возвращает резервную копию.
 */
class BackupCollection extends CmsCollection
{
    /**
     * Настройка для задания максимального количества резервных копий для одного объекта
     */
    const SETTING_OBJECT_HISTORY_SIZE = 'objectHistorySize';

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
                    Backup::FIELD_CREATED,
                    Backup::FIELD_OWNER
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
            ->orderBy(Backup::FIELD_CREATED, CmsSelector::ORDER_DESC);
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
     * Проверяет, включены ли резервные копии.
     * @return bool
     */
    public function isBackupEnabled()
    {
        return (bool) $this->getSetting(self::SETTING_OBJECT_HISTORY_SIZE);
    }

    /**
     * Создаёт резервную копию объекта.
     * @param IRecoverableObject $object
     * @return $this
     */
    public function createBackup(IRecoverableObject $object)
    {
        if (!$this->isBackupEnabled()) {
            return $this;
        }

        $this->clearOldBackups($object);

        $backup = $this->add();
        $backup->objectId = $object->getId();
        $backup->collectionName = $object->getCollectionName();
        $backup->getProperty(Backup::FIELD_DATA)->setValue(serialize($object));

        return $this;
    }

    /**
     * Очищает устаревшие резервные копии объекта.
     * @param IRecoverableObject $object
     */
    protected function clearOldBackups(IRecoverableObject $object)
    {
        $historySize = (int) $this->getSetting(self::SETTING_OBJECT_HISTORY_SIZE) - 1;

        $oldBackups = $this->getList($object);

        if ($historySize > 0) {
            $oldBackups->limit(PHP_INT_MAX, $historySize);
        }

        foreach ($oldBackups as $oldBackup) {
            $this->delete($oldBackup);
        }
    }
}
 