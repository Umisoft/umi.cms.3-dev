<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\model\object\BaseMenu;

/**
 * Коллекция для работы с меню.
 *
 * @method CmsSelector|BaseMenu[] select() Возвращает селектор для выбора меню.
 * @method BaseMenu get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает меню по GUID.
 * @method BaseMenu getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает меню по id.
 * @method BaseMenu add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null) Добавляет меню.
 */
class MenuCollection extends CmsHierarchicCollection
{

}
