<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogRssImportScenario;

/**
 * Коллекция сценариев RSS-ипорта.
 *
 * @method CmsSelector|BlogRssImportScenario[] select() Возвращает селектор для выбора сценариев импорта.
 * @method BlogRssImportScenario get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по GUID
 * @method BlogRssImportScenario getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по id
 * @method BlogRssImportScenario add($typeName = IObjectType::BASE) Создает и возвращает сценарий импорта
 *
 */
class BlogRssImportScenarioCollection extends SimpleCollection
{

}
