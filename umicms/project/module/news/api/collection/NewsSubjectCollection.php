<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\PageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Коллекция для работы с новостными сюжетами.
 *
 * @method CmsSelector|NewsSubject[] select() Возвращает селектор для выбора сюжетов.
 * @method NewsSubject get($guid, $withLocalization = false) Возвращает сюжет по GUID
 * @method NewsSubject getById($objectId, $withLocalization = false) Возвращает сюжет по id
 * @method NewsSubject add($typeName = IObjectType::BASE) Создает и возвращает сюжет
 * @method NewsSubject getBySlug($slug, $withLocalization = false) Возвращает сюжет по его последней части ЧПУ
 */
class NewsSubjectCollection extends PageCollection
{

}
