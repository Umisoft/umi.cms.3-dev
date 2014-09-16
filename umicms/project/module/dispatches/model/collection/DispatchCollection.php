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
use umicms\project\module\dispatches\model\object\Dispatch;

/**
 * Коллекция для работы с расслыками.
 *
 * @method Dispatch get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает рассылку по ее GUID.
 * @method Dispatch getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает рассылку по ее id.
 * @method Dispatch add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает рассылку.
 */
class DispatchCollection extends CmsCollection
{

}
