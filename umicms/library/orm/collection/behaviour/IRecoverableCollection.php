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

use umicms\exception\InvalidArgumentException;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\model\object\Backup;

/**
 * Интерфейс коллекций, поддерживающих создание резервных копий объектов.
 */
interface IRecoverableCollection extends ICmsCollection
{
    /**
     * Создает резервную копию объекта.
     * @param IRecoverableObject $object
     * @throws NotAllowedOperationException если объект не принадлежит коллекции.
     * @return $this
     */
    public function createBackup(IRecoverableObject $object);

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

    /**
     * Проверяет, включены ли резервные копии.
     * @return bool
     */
    public function isBackupEnabled();
}