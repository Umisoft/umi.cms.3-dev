<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umi\orm\collection\ICollectionManager;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\project\module\service\api\collection\BackupCollection;

/**
 * Трейт для коллекций, поддерживающих резервное копирование объектов.
 */
trait TRecoverableCollection
{
    /**
     * @see ICmsCollection::getName()
     */
    abstract public function getName();
    /**
     * @see TCollectionManagerAware::getCollectionManager()
     * @return ICollectionManager
     */
    abstract protected function getCollectionManager();
    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see IRecoverableCollection::createBackup()
     */
    public function createBackup(IRecoverableObject $object)
    {
        if ($object->getCollection() !== $this) {
            throw new NotAllowedOperationException($this->translate(
                'Cannot create object backup. Object from another collection given.'
            ));
        }

        return $this->getBackupCollection()->createBackup($object);
    }

    /**
     * @see IRecoverableCollection::getBackupList()
     */
    public function getBackupList(IRecoverableObject $object)
    {
        if ($object->getCollection() != $this) {
            throw new NotAllowedOperationException($this->translate(
                'Cannot get object backups. Object from another collection given.'
            ));
        }

        return $this->getBackupCollection()->getList($object);
    }


    /**
     * @see IRecoverableCollection::wakeUpBackup()
     */
    public function wakeUpBackup(IRecoverableObject $object, $backupId)
    {
        if ($object->getCollection() !== $this) {
            throw new NotAllowedOperationException($this->translate(
                'Cannot get object backups. Object from another collection given.'
            ));
        }

        return $this->getBackupCollection()->wakeUpBackup($object, $backupId);
    }

    /**
     * Возвращает коллекцию резервных копий.
     * @return BackupCollection
     */
    private function getBackupCollection()
    {
        return $this->getCollectionManager()->getCollection('serviceBackup');
    }
}
 