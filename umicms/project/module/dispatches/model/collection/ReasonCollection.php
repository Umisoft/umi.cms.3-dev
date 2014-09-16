<?php
/**
 * This file is part of UMI.CMS.
 *
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
use umicms\project\module\dispatches\model\object\Reason;

/**
 * Коллекция для работы с причинами отписки.
 *
 * @method CmsSelector|Reason[] select() Возвращает селектор для выбора причин отписок.
 * @method Reason get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает причину отписки по ее GUID.
 * @method Reason getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает причину отписки по ее id.
 * @method Reason add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает причину отписки.
 */
class ReasonCollection extends CmsCollection
{

}
