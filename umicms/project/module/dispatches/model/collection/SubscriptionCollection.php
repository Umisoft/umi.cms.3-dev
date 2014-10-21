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
use umicms\project\module\dispatches\model\object\Subscription;

/**
 * Коллекция для работы с подписчиками.
 *
 * @method CmsSelector|Subscription[] select() Возвращает селектор для выбора подписок.
 * @method Subscription get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает подписку по GUID.
 * @method Subscription getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает подписку по id.
 * @method Subscription add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает подписку.
 */
class SubscriptionCollection extends CmsCollection
{

}
