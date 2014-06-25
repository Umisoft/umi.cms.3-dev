<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection\behaviour;

use umi\orm\collection\ICollectionManager;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\project\module\service\model\collection\BackupCollection;

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
     * @see IRecoverableCollection::isBackupEnabled()
     */
    public function isBackupEnabled()
    {
        return $this->getBackupCollection()->isBackupEnabled();
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
 