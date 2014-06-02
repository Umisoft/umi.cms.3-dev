<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\api\object\InfoBlock;

/**
 * Коллекция для работы с информационными блоками.
 *
 * @method CmsSelector|InfoBlock[] select() Возвращает селектор для выбора информационных блоков.
 * @method InfoBlock get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает информационный блок по GUID.
 * @method InfoBlock getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает информационный блок по id.
 * @method InfoBlock add($typeName = IObjectType::BASE) Создает и возвращает информационный блок.
 */
class InfoBlockCollection extends SimpleCollection
{

}
