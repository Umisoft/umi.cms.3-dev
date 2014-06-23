<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object\behaviour;

use umicms\orm\object\ICmsObject;

/**
 * Интерфейс объекта, который может иметь состояние заблокированности на удаление и некоторые операции.
 *
 * @property bool $locked состояние заблокированности
 */
interface ILockedAccessibleObject extends ICmsObject
{
    /**
     *  Имя поля для хранения состояния заблокированности
     */
    const FIELD_LOCKED = 'locked';
}
 