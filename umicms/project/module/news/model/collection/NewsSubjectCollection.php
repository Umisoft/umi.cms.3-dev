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
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\model\object\NewsSubject;

/**
 * Коллекция для работы с новостными сюжетами.
 *
 * @method CmsSelector|NewsSubject[] select() Возвращает селектор для выбора сюжетов.
 * @method NewsSubject get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сюжет по GUID
 * @method NewsSubject getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сюжет по id
 * @method NewsSubject  add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает сюжет
 * @method NewsSubject getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает сюжет по его последней части ЧПУ
 */
class NewsSubjectCollection extends CmsPageCollection
{

}
