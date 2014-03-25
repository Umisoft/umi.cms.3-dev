<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\object;

use umi\orm\object\property\datetime\DateTime;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;
use umicms\project\module\users\object\AuthorizedUser;

/**
 * Бэкап объекта CMS.
 *
 * @property int $objectId идентификатор объекта, которому принадлежит резервная копия
 * @property string $collectionName имя коллекции к которой относится объект
 * @property DateTime $date дата
 * @property AuthorizedUser $user пользователь редактировавший объект
 * @property ICmsObject $data данные резервной копии объекта
 */
class Backup extends CmsObject
{
    /**
     * Имя поля для хранения идентификатора объекта, которому принадлежит резервная копия
     */
    const FIELD_OBJECT_ID = 'objectId';
    /**
     * Имя поля для хранения имени коллекции к которой относится объект
     */
    const FIELD_COLLECTION_NAME = 'collectionName';
    /**
     * Имя поля для хранения даты создания резервной копии
     */
    const FIELD_DATE = 'date';
    /**
     * Имя поля для хранения пользователя редактировавшего страницу
     */
    const FIELD_USER = 'user';
    /**
     * Имя поля для хранения данных резервной копии
     */
    const FIELD_DATA = 'data';

    /**
     * Возвращает данные резервной копии объекта.
     * @return array
     */
    public function getData() {
        if ($value = $this->getProperty(self::FIELD_DATA)->getValue()) {
            return unserialize($value);
        }
        return [];
    }
}
 