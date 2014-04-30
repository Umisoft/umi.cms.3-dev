<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\testmodule\api\object\TestObject;

/**
 * Коллекция тестовая.
 *
 * @method CmsSelector|TestObject[] select() Возвращает селектор для выбора тестовых записей.
 * @method TestObject get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тестовую запись по ее GUID.
 * @method TestObject getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тестовую запись по ее id.
 * @method TestObject add($typeName = IObjectType::BASE) Создает и возвращает тестовую запись.
 */
class TestCollection extends SimpleCollection
{

}
 