<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\surveys\model\object\Answer;

/**
 * Коллекция для работы с вариантами ответов для опросов.
 *
 * @method CmsSelector|Answer[] select() Возвращает селектор для выбора ответов.
 * @method Answer get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает ответ по его GUID.
 * @method Answer getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает ответ по его id.
 * @method Answer add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает ответ.
 */
class AnswerCollection extends CmsCollection
{

}
