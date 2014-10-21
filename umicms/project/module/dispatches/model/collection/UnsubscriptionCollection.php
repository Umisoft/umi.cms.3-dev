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
use umicms\project\module\dispatches\model\object\Unsubscription;

/**
 * Коллекция для работы с подписчиками.
 *
 * @method CmsSelector|Unsubscription[] select() Возвращает селектор для выбора подписок.
 * @method Unsubscription get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает подписку по GUID.
 * @method Unsubscription getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает подписку по id.
 * @method Unsubscription add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает подписку.
 */
class UnsubscriptionCollection extends CmsCollection
{

}
