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
use umicms\project\module\dispatches\model\object\ReleaseStatus;

/**
 * Коллекция для работы со статусами выпусков рассылки.
 *
 * @method CmsSelector|ReleaseStatus[] select() Возвращает селектор для выбора статусов.
 * @method ReleaseStatus get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает статус по GUID.
 * @method ReleaseStatus getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает статус по id.
 * @method ReleaseStatus add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает статус.
 */
class ReleaseStatusCollection extends CmsCollection
{

}
