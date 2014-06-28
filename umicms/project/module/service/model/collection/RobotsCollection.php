<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\project\module\service\model\object\Robots;

/**
 * Коллекция страниц входящих в robots.txt.
 *
 * @method Robots get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о странице по ее GUID
 * @method Robots getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о странице по ее id
 * @method Robots add($typeName = IObjectType::BASE) Добавляет запись о странице и возвращает её
 */
class RobotsCollection extends CmsCollection
{
}
