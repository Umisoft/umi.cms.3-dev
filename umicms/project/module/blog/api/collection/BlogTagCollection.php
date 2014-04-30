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
use umicms\orm\collection\PageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogTag;

/**
 * Коллекция тэгов блога.
 *
 * @method CmsSelector|BlogTag[] select() Возвращает селектор для выбора тэгов.
 * @method BlogTag get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тэг по GUID
 * @method BlogTag getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тэг по id
 * @method BlogTag add($typeName = IObjectType::BASE) Создает и возвращает тэг
 * @method BlogTag getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тэг по его последней части ЧПУ
 */
class BlogTagCollection extends PageCollection
{

}
