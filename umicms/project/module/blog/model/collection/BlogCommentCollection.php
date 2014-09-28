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
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BaseBlogPost;
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\blog\model\object\BlogComment;

/**
 * Коллекция комментариев блога.
 *
 * @method CmsSelector|BlogComment[] select() Возвращает селектор для выбора комментария блога.
 * @method BlogComment get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает комментарий блога по его GUID.
 * @method BlogComment getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает комментарий блога по его id
 * @method BlogComment add($slug = null, $typeName = IObjectType::BASE, IHierarchicObject $branch = null, $guid = null) Создает и возвращает комментарий блога
 */
class BlogCommentCollection extends CmsHierarchicCollection implements IRecyclableCollection
{
    use TRecyclableCollection {
        TRecyclableCollection::trash as protected trashInternal;
        TRecyclableCollection::untrash as protected untrashInternal;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'collection:blogComment';
    }

    /**
     * {@inheritdoc}
     */
    public function delete(IObject $object)
    {
        if ($object instanceof BlogComment) {
            if ($object->author instanceof BlogAuthor) {
                $object->author->recalculateCommentsCount();
            }
            if ($object->post instanceof BaseBlogPost) {
                $object->post->recalculateCommentsCount();
            }
        }

        return parent::delete($object);
    }

    /**
     * {@inheritdoc}
     */
    public function trash(IRecyclableObject $object)
    {
        if ($object instanceof BlogComment) {
            if ($object->author instanceof BlogAuthor) {
                $object->author->recalculateCommentsCount();
            }
            if ($object->post instanceof BaseBlogPost) {
                $object->post->recalculateCommentsCount();
            }
        }

        return $this->trashInternal($object);
    }

    /**
     * {@inheritdoc}
     */
    public function untrash(IRecyclableObject $object)
    {
        if ($object instanceof BlogComment) {
            if ($object->author instanceof BlogAuthor) {
                $object->author->recalculateCommentsCount();
            }
            if ($object->post instanceof BaseBlogPost) {
                $object->post->recalculateCommentsCount();
            }
        }

        return $this->untrashInternal($object);
    }
}
 