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

use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\ILockedAccessibleObject;

/**
 * Интерфейс коллекций, поддерживающих управление заблокированнойстью объекта на удаление и некоторые операции.
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
 