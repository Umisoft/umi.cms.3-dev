<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * Коллекция тем форума.
 *
 * @method CmsSelector|ForumTheme[] select() Возвращает селектор для выбора конференций.
 * @method ForumTheme get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по GUID
 * @method ForumTheme getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по id
 * @method ForumTheme add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает конференцию
 * @method ForumTheme getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по его последней части ЧПУ
 */
class ForumThemeCollection extends CmsPageCollection
{
}
