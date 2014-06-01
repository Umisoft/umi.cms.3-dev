<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\api\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;
use umicms\project\module\users\api\object\AuthorizedUser;

/**
 * Бэкап объекта CMS.
 *
 * @property int $objectId идентификатор объекта, которому принадлежит резервная копия
 * @property string $collectionName имя коллекции к которой относится объект
 * @property \DateTime $date дата
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
 