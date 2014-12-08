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
use umi\orm\collection\ISimpleHierarchicCollection;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * Коллекция тем форума.
 *
 * @method CmsSelector|ForumTheme[] select() Возвращает селектор для выбора темы форума.
 * @method ForumTheme get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тему форума по его GUID.
 * @method ForumTheme getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тему форума по его id
 * @method ForumTheme add($slug = null, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null) Создает и возвращает тему форума
 */
class ForumThemeCollection extends CmsHierarchicCollection implements IRecyclableCollection
{
    use TRecyclableCollection {
        TRecyclableCollection::trash as protected trashInternal;
        TRecyclableCollection::untrash as protected untrashInternal;
    }
}
