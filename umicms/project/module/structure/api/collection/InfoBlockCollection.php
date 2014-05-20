<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
