<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\object;

use DateTime;
use umi\orm\metadata\IObjectType;

/**
 * Интерфейс ORM-объекта для UMI.CMS.
 *
 * @property string $guid глобальный уникальный идентификатор (GUID)
 * @property IObjectType $type тип
 * @property string $displayName выводимое в интерфейсах имя
 * @property int $version версия
 * @property bool $active признак активности
 * @property bool $locked признак заблокированности элемента на удаление
 * @property DateTime $created время создания элемента
 * @property DateTime $updated время обновления элемента
 */
interface ICmsObject
{
    /**
     *  Имя поля для хранения состояния активности объекта
     */
    const FIELD_ACTIVE = 'active';
    /**
     *  Имя поля для хранения состояния заблокированности объекта на удаление
     */
    const FIELD_LOCKED = 'locked';
    /**
     *  Имя поля для хранения времени создания объекта
     */
    const FIELD_CREATED = 'created';
    /**
     *  Имя поля для хранения времени последнего изменения объекта
     */
    const FIELD_UPDATED = 'updated';
}
