<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\collection;

use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogComment;

/**
 * Коллекция комментариев блога.
 *
 * @method CmsSelector|BlogComment[] select() Возвращает селектор для выбора комментария блога.
 * @method BlogComment get($guid, $withLocalization = false) Возвращает комментарий блога по его GUID.
 * @method BlogComment getById($objectId, $withLocalization = false) Возвращает комментарий блога по его id
 * @method BlogComment add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Создает и возвращает комментарий блога
 */
class BlogCommentCollection extends SimpleHierarchicCollection implements IActiveAccessibleCollection
{
    use TActiveAccessibleCollection;
}
 