<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\collection;

use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\PageHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogCategory;


/**
 * Коллекция категорий блога.
 *
 * @method CmsSelector|BlogCategory[] select() Возвращает селектор для выбора категорий блога.
 * @method BlogCategory get($guid, $withLocalization = false) Возвращает категорию блога по ее GUID.
 * @method BlogCategory getById($objectId, $withLocalization = false) Возвращает категорию блога по ее id
 * @method BlogCategory getByUri($uri, $withLocalization = false) Возвращает категорию блога по ее URI
 * @method BlogCategory add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Создает и возвращает категорию блога
 */
class BlogCategoryCollection extends PageHierarchicCollection
{

}
