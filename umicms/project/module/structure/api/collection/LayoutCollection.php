<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\structure\api\object\Layout;
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
class LayoutCollection extends SimpleCollection implements ILockedAccessibleCollection, ISiteSettingsAware
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
