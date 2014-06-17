<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\testmodule\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\testmodule\model\object\TestObject;

/**
 * Коллекция тестовая.
 *
 * @method CmsSelector|TestObject[] select() Возвращает селектор для выбора тестовых записей.
 * @method TestObject get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тестовую запись по ее GUID.
 * @method TestObject getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тестовую запись по ее id.
 * @method TestObject add($typeName = IObjectType::BASE) Создает и возвращает тестовую запись.
 */
class TestCollection extends CmsCollection
{

}
 