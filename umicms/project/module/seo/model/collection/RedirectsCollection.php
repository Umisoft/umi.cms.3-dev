<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\project\module\seo\model\object\Redirect;

/**
 * Коллекция редиректов для SEO
 *
 * @method Redirect get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о редиректе по $guid
 * @method Redirect getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о редиректе по ее id
 * @method Redirect add($typeName = IObjectType::BASE, $guid = null) Добавляет запись о редиректе и возвращает её
 */
class RedirectsCollection extends CmsCollection
{

}
