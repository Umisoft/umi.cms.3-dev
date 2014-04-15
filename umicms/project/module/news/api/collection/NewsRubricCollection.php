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
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\PageHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsRubric;

/**
 * Коллекция для работы с новостными рубриками.
 *
 * @method CmsSelector|NewsRubric[] select() Возвращает селектор для выбора новостных рубрик.
 * @method NewsRubric get($guid, $withLocalization = false) Возвращает рубрику по ее GUID.
 * @method NewsRubric getById($objectId, $withLocalization = false) Возвращает рубрику по ее id
 * @method NewsRubric getByUri($uri, $withLocalization = false) Возвращает новостую рубрику по ее URI
 * @method NewsRubric add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Создает и возвращает рубрику
 */
class NewsRubricCollection extends PageHierarchicCollection
{

}
