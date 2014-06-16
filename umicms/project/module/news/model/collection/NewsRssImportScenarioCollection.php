<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\model\object\NewsRssImportScenario;

/**
 * Коллекция сценариев RSS-ипорта.
 *
 * @method CmsSelector|NewsRssImportScenario[] select() Возвращает селектор для выбора сценариев импорта.
 * @method NewsRssImportScenario get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по GUID
 * @method NewsRssImportScenario getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сценарий импорта по id
 * @method NewsRssImportScenario add($typeName = IObjectType::BASE) Создает и возвращает сценарий импорта
 *
 */
class NewsRssImportScenarioCollection extends CmsCollection
{

}
