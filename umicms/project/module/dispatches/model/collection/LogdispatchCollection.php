<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\object\Logdispatch;

/**
 * Коллекция для работы с логами расслок.
 *
 * @method CmsSelector|Logdispatch[] select() Возвращает селектор для выбора рассылок.
 * @method Logdispatch get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает лог рассылки по его GUID.
 * @method Logdispatch getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает лог рассылки по его id.
 * @method Logdispatch add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает лог рассылки.
 */
class LogdispatchCollection extends CmsCollection
{

}
