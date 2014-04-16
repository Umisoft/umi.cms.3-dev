<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsRssImportScenario;

/**
 * Коллекция сценариев RSS-ипорта.
 *
 * @method CmsSelector|NewsRssImportScenario[] select() Возвращает селектор для выбора сценариев импорта.
 * @method NewsRssImportScenario get($guid, $withLocalization = false) Возвращает сценарий импорта по GUID
 * @method NewsRssImportScenario getById($objectId, $withLocalization = false) Возвращает сценарий импорта по id
 * @method NewsRssImportScenario add($typeName = IObjectType::BASE) Создает и возвращает сценарий импорта
 *
 */
class NewsRssImportScenarioCollection extends SimpleCollection
{

}
