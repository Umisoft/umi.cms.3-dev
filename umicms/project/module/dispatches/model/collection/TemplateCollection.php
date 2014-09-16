<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\object\Template;

/**
 * Коллекция для работы с шаблонами писем для выпуска рассылки.
 *
 * @method CmsSelector|Template[] select() Возвращает селектор для выбора шаблонов.
 * @method Template get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает шаблон по GUID.
 * @method Template getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает шаблон по id.
 * @method Template add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает шаблон.
 */
class TemplateCollection extends CmsCollection
{

}
