<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsObject;

/**
 * Бэкап объекта CMS.
 *
 * @property int $objectId идентификатор объекта, которому принадлежит резервная копия
 * @property string $refCollectionName имя коллекции к которой относится объект
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
    const FIELD_REF_COLLECTION_NAME = 'refCollectionName';
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
 