<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use DateTime;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IObject;
use umicms\project\module\users\api\object\BaseUser;

/**
 * Интерфейс ORM-объекта для UMI.CMS.
 *
 * @property string $guid глобальный уникальный идентификатор (GUID)
 * @property IObjectType $type тип
 * @property string $displayName выводимое в интерфейсах имя
 * @property int $version версия
 * @property DateTime $created время создания объекта
 * @property DateTime $updated время обновления объекта
 * @property BaseUser $owner владелец объекта
 * @property BaseUser $editor последний редактор объекта
 */
interface ICmsObject extends IObject
{
    /**
     *  Имя поля для хранения времени создания объекта
     */
    const FIELD_CREATED = 'created';
    /**
     *  Имя поля для хранения времени последнего изменения объекта
     */
    const FIELD_UPDATED = 'updated';
    /**
     * Имя поля для хранения названия объекта
     */
    const FIELD_DISPLAY_NAME = 'displayName';
    /**
     * Имя поля для хранения владельца объекта
     */
    const FIELD_OWNER = 'owner';
    /**
     * Имя поля для хранения последнего редактора объекта
     */
    const FIELD_EDITOR = 'editor';

    /**
     * Возвращает ссылку на редактирование объекта в административной панели.
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getEditLink($isAbsolute = false);
}
