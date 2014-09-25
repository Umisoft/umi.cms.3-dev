<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\IUserAssociatedCollection;
use umicms\orm\collection\behaviour\TUserAssociatedCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\users\model\object\BaseUser;

/**
 * Коллекция авторов блога.
 *
 * @method CmsSelector|BlogAuthor[] select() Возвращает селектор для выбора авторов.
 * @method BlogAuthor get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает автора по GUID
 * @method BlogAuthor getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает автора по id
 * @method BlogAuthor add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает автора
 * @method BlogAuthor getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает автора по его последней части ЧПУ
 */
class BlogAuthorCollection extends CmsPageCollection implements IUserAssociatedCollection
{
    use TUserAssociatedCollection;

    /**
     * Создает и возвращает автора для пользователя
     * @param BaseUser $user пользователь
     * @param string $type тип создаваемого автора
     * @param null $guid GUID создаваемого автора
     * @return BlogAuthor
     */
    public function createForUser(BaseUser $user, $type = IObjectType::BASE, $guid = null) {

        $author = $this->add($type, $guid);
        $this->fillFromUser($user, $author);

        $author->slug = $user->displayName;
        $author->active = true;

        return $author;

    }
}
