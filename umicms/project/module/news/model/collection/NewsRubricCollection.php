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
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\CmsHierarchicPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\model\object\NewsRubric;

/**
 * Коллекция для работы с новостными рубриками.
 *
 * @method CmsSelector|NewsRubric[] select() Возвращает селектор для выбора новостных рубрик.
 * @method NewsRubric get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает рубрику по ее GUID.
 * @method NewsRubric getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает рубрику по ее id
 * @method NewsRubric getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает новостую рубрику по ее URI
 * @method NewsRubric add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Создает и возвращает рубрику
 */
class NewsRubricCollection extends CmsHierarchicPageCollection
{

}
