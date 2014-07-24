<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model;

use umicms\module\BaseModule;
use umicms\project\module\service\model\collection\BackupCollection;

/**
 * Модуль "Сервис"
*/
class ServiceModule extends BaseModule
{
    /**
     * Возвращает коллекцию бэкапов.
     * @return BackupCollection
     */
    public function backup()
    {
        return $this->getCollection('backup');
    }

    /**
     * Возвращает API для работы с лицензией.
     * @return LicenseApi
     */
    public function license()
    {
        return $this->getApi('umicms\project\module\service\model\LicenseApi');
    }
}
 