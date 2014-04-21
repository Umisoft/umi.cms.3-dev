<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
 