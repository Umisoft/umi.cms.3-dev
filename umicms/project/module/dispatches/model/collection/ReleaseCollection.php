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
use umicms\project\module\dispatches\model\object\Release;

/**
 * Коллекция для работы с выпусками рассылок.
 *
 * @method CmsSelector|Release[] select() Возвращает селектор для выбора выпусков рассылки.
 * @method Release get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает выпуск рассылки по GUID.
 * @method Release getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает выпуск рассылки по id.
 * @method Release add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает выпуск рассылки.
 */
class ReleaseCollection extends CmsCollection
{

}
