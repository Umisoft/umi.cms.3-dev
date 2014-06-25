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

use umi\acl\IAclResource;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\blog\model\object\BlogComment;

/**
 * Коллекция комментариев блога.
 *
 * @method CmsSelector|BlogComment[] select() Возвращает селектор для выбора комментария блога.
 * @method BlogComment get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает комментарий блога по его GUID.
 * @method BlogComment getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает комментарий блога по его id
 * @method BlogComment add($slug, $typeName = IObjectType::BASE, IHierarchicObject $branch = null) Создает и возвращает комментарий блога
 */
class BlogCommentCollection extends CmsHierarchicCollection implements IActiveAccessibleCollection, IAclResource
{
    use TActiveAccessibleCollection;

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'collection:blogComment';
    }

    /**
     * Публикует комментарий.
     * @param BlogComment $comment публикуемый комментарий
     * @return BlogComment
     */
    public function publish(BlogComment $comment)
    {
        $comment->publish();
        if ($comment->author instanceof BlogAuthor) {
            $comment->author->incrementCommentCount();
        }
        $comment->post->incrementCommentCount();

        return $comment;
    }
}
 