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
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\forum\model\object\ForumConference;

/**
 * Коллекция конференций форума.
 *
 * @method CmsSelector|ForumConference[] select() Возвращает селектор для выбора конференций.
 * @method ForumConference get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по GUID
 * @method ForumConference getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по id
 * @method ForumConference add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает конференцию
 * @method ForumConference getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает конференцию по его последней части ЧПУ
 */
class ForumConferenceCollection extends CmsPageCollection implements IRecyclableCollection
{
    use TRecyclableCollection {
        TRecyclableCollection::trash as protected trashInternal;
        TRecyclableCollection::untrash as protected untrashInternal;
    }
}
