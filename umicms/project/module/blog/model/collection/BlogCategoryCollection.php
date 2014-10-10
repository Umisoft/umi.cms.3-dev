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
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\CmsHierarchicPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BlogCategory;


/**
 * Коллекция категорий блога.
 *
 * @method CmsSelector|BlogCategory[] select() Возвращает селектор для выбора категорий блога.
 * @method BlogCategory get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает категорию блога по ее GUID.
 * @method BlogCategory getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает категорию блога по ее id
 * @method BlogCategory getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает категорию блога по ее URI
 * @method BlogCategory add($slug = null, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null) Создает и возвращает категорию блога
 */
class BlogCategoryCollection extends CmsHierarchicPageCollection
{

}
