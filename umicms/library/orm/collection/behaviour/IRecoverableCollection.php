<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\exception\InvalidArgumentException;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\api\object\Backup;

/**
 * Интерфейс коллекций, поддерживающих создание резервных копий объектов.
 */
interface IRecoverableCollection extends ICmsCollection
{
    /**
     * Возвращает список резервных копий для объекта.
     * @param IRecoverableObject $object
     * @throws NotAllowedOperationException если объект не принадлежит коллекции.
     * @return CmsSelector|Backup[]
     */
    public function getBackupList(IRecoverableObject $object);

    /**
     * Возвращает резервную копию объекта.
     * @param IRecoverableObject $object
     * @param int $backupId идентификатор резервной копии
     * @throws NotAllowedOperationException если объект не принадлежит коллекции
     * @throws InvalidArgumentException если резервная копия не принадлежит указанному объекту
     * @return IRecoverableObject
     */
    public function wakeUpBackup(IRecoverableObject $object, $backupId);
}