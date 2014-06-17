<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BlogRssImportScenario;

/**
 * Коллекция сценариев RSS-ипорта.
 *
 * @method CmsSelector|BlogRssImportScenario[] select() Возвращает селектор для выбора сценариев импорта.
 * @method BlogRssImportScenario get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по GUID
 * @method BlogRssImportScenario getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по id
 * @method BlogRssImportScenario add($typeName = IObjectType::BASE) Создает и возвращает сценарий импорта
 *
 */
class BlogRssImportScenarioCollection extends CmsCollection
{

}
