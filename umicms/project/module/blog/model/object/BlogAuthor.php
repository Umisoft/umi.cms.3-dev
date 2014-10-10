<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\object;

use DateTime;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\object\property\calculable\ICalculableProperty;
use umi\orm\objectset\IObjectSet;
use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\blog\model\collection\BlogCommentCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;

/**
 * Автор поста.
 *
 * @property int $postsCount количество постов автора
 * @property int $commentsCount количество постов автора
 * @property DateTime $lastActivity дата последней активности
 * @property IObjectSet $posts посты автора
 * @property IObjectSet $comments комментарии
 */
class BlogAuthor extends CmsObject implements ICmsPage, IUserAssociatedObject
{
    use TCmsPage;

    /**
     * Имя поля для хранения необработанного контента
     */
    const FIELD_PAGE_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения количества постов автора
     */
    const FIELD_POSTS_COUNT = 'postsCount';
    /**
     * Имя поля для хранения количества комментариев автора
     */
    const FIELD_COMMENTS_COUNT = 'commentsCount';
    /**
     * Имя поля для хранения даты последней активности автора
     */
    const FIELD_LAST_ACTIVITY = 'lastActivity';
    /**
     * Имя поля для хранения постов автора
     */
    const FIELD_POSTS = 'posts';
    /**
     * Имя поля для хранения комментариев к посту
     */
    const FIELD_COMMENTS = 'comments';
    /**
     * Форма редактирования профиля автора
     */
    const FORM_EDIT_PROFILE = 'editProfile';

    /**
     * Метод мутатор для контентного поля.
     * @param string $contents контент профиля автора
     * @param string $locale локаль
     * @return $this
     */
    public function setContents($contents, $locale)
    {
        $this->getProperty(self::FIELD_PAGE_CONTENTS, $locale)
            ->setValue($contents);

        $this->getProperty(self::FIELD_PAGE_CONTENTS_RAW, $locale)
            ->setValue($contents);

        return $this;
    }

    /**
     * Помечает количество комментариев для пересчета.
     * @return $this
     */
    public function recalculateCommentsCount()
    {
        /**
         * @var ICalculableProperty $commentsCountProperty
         */
        $commentsCountProperty = $this->getProperty(self::FIELD_COMMENTS_COUNT);
        $commentsCountProperty->recalculate();

        return $this;
    }

    /**
     * Вычисляет количество опубликованных комментариев автора.
     * @return int
     */
    public function calculateCommentsCount()
    {
        /**
         * @var HasManyRelationField $commentsField
         */
        $commentsField = $this->getProperty(self::FIELD_COMMENTS)->getField();
        /**
         * @var BlogCommentCollection $commentCollection
         */
        $commentCollection = $commentsField->getTargetCollection();

        return $commentCollection->getInternalSelector()
            ->fields([BlogComment::FIELD_IDENTIFY])
            ->types([BlogComment::TYPE_NAME . '*'])
            ->where(BlogComment::FIELD_AUTHOR)
                ->equals($this)
            ->where(BlogComment::FIELD_STATUS . '.' . CommentStatus::FIELD_GUID)
                ->equals(CommentStatus::GUID_PUBLISHED)
            ->where(BlogComment::FIELD_TRASHED)
                ->equals(false)
            ->getTotal();
    }

    /**
     * Помечает количество постов для пересчета.
     * @return $this
     */
    public function recalculatePostsCount()
    {
        $postsCountProperty = $this->getProperty(self::FIELD_POSTS_COUNT);
        foreach ($postsCountProperty->getField()->getLocalizations() as $localeId => $localeInfo) {
            /**
             * @var ICalculableProperty $localizedPostsCountProperty
             */
            $localizedPostsCountProperty = $this->getProperty(self::FIELD_POSTS_COUNT, $localeId);
            $localizedPostsCountProperty->recalculate();
        }

        return $this;
    }

    /**
     * Вычисляет количество опубликованных постов автора.
     * @param string|null $localeId
     * @return int
     */
    public function calculatePostsCount($localeId = null)
    {
        /**
         * @var HasManyRelationField $postsField
         */
        $postsField = $this->getProperty(self::FIELD_POSTS)->getField();
        /**
         * @var BlogPostCollection $postCollection
         */
        $postCollection = $postsField->getTargetCollection();

        return $postCollection->getInternalSelector()
            ->fields([BlogPost::FIELD_IDENTIFY])
            ->where(BlogPost::FIELD_AUTHOR)
                ->equals($this)
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
                ->equals(PostStatus::GUID_PUBLISHED)
            ->where(BlogPost::FIELD_ACTIVE, $localeId)
                ->equals(true)
            ->where(BlogPost::FIELD_TRASHED)
                ->equals(false)
            ->getTotal();
    }
}
 