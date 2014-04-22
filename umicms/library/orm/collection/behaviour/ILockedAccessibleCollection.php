<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;

/**
 * Интерфейс коллекций, поддерживающих управлению заблокированнойстью объекта на удаление и некоторые операции.
 */
interface ILockedAccessibleCollection extends ICmsCollection
{
    /**
     * Блокирует объект.
     * @param ILockedAccessibleObject $object
     * @return $this
     */
    public function lock(ILockedAccessibleObject $object);

    /**
     * Разблокирует объект.
     * @param ILockedAccessibleObject $object
     * @return $this
     */
    public function unlock(ILockedAccessibleObject $object);
}
 