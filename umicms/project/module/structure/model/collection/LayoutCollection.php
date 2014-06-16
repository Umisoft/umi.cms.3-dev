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
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\model\object\Layout;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Коллекция для работы с шаблонами.
 *
 * @method CmsSelector|Layout[] select() Возвращает селектор для выбора шаблонов.
 * @method Layout get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает шаблон по GUID.
 * @method Layout getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает шаблон по id.
 * @method Layout add($typeName = IObjectType::BASE) Создает и возвращает шаблон.
 */
class LayoutCollection extends CmsCollection implements ILockedAccessibleCollection, ISiteSettingsAware
{
    use TLockedAccessibleCollection;
    use TSiteSettingsAware;

    /**
     * Возвращает шаблон сайта по умолчанию
     * @return Layout
     */
    public function getDefaultLayout()
    {
        return $this->get($this->getSiteDefaultLayoutGuid());
    }

}
