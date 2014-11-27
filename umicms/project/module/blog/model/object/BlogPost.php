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
use umi\hmvc\acl\ComponentRoleProvider;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\object\property\calculable\ICalculableProperty;
use umi\orm\objectset\IManyToManyObjectSet;
use umi\orm\objectset\IObjectSet;
use umicms\exception\RuntimeException;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\blog\model\collection\BlogCommentCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Базовый тип поста блога.
 *
 * @property string $contentsRaw необработанный контент поста
 * @property string $announcement анонс
 * @property BlogCategory|null $category категория поста
 * @property IManyToManyObjectSet $tags теги, к которым относится пост
 * @property DateTime $publishTime дата публикации поста
 * @property PostStatus $status статус публикации поста
 * @property IObjectSet $comments комментарии
 * @property int $commentsCount количество комментариев к посту
 * @property string $source источник поста
 * @property string $image ссылка на картинку
 * @property BlogAuthor $author автор поста
 */
class BlogPost extends CmsObject implements ICmsPage, IModuleAware
{
    use TCmsPage;
    use TModuleAware;

    /**
     * Имя поля для хранения необработанного контента поста
     */
    const FIELD_PAGE_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения анонса поста
     */
    const FIELD_ANNOUNCEMENT = 'announcement';
    /**
     * Имя поля для хранения названия категории, к которой относится пост
     */
    const FIELD_CATEGORY = 'category';
    /**
     * Имя поля для хранения тэгов, относящихся к посту
     */
    const FIELD_TAGS = 'tags';
    /**
     * Имя поля для хранения даты и времени публикации поста
     */
    const FIELD_PUBLISH_TIME = 'publishTime';
    /**
     * Имя поля для хранения статуса публикации поста
     */
    const FIELD_STATUS = 'status';
    /**
     * Имя поля для хранения количества комментариев к посту
     */
    const FIELD_COMMENTS_COUNT = 'commentsCount';
    /**
     * Имя поля для хранения комментариев к посту
     */
    const FIELD_COMMENTS = 'comments';
    /**
     * Имя поля для хранения источника поста
     */
    const FIELD_SOURCE = 'source';
    /**
     * Имя поля для хранения картинки
     */
    const FIELD_IMAGE = 'image';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';

    /**
     * Форма отправки поста на модерацию
     */
    const FORM_MODERATE_POST = 'moderate';
    /**
     * Форма помещения поста в черновики
     */
    const FORM_DRAFT_POST = 'draft';
    /**
     * Форма добавления поста зарегистрированным пользователем
     */
    const FORM_ADD_POST = 'addPost';
    /**
     * Форма добавления поста незарегистрированным посетителем
     */
    const FORM_ADD_VISITOR_POST = 'addVisitorPost';
    /**
     * Форма редактирования поста
     */
    const FORM_EDIT_POST = 'editPost';
    /**
     * Форма публикации поста
     */
    const FORM_PUBLISH_POST = 'publish';
    /**
     * Форма отклонения поста для публикации
     */
    const FORM_REJECT_POST = 'reject';

    /**
     * Возвращает URL поста.
     * @param bool $isAbsolute абсолютный ли URL
     * @throws RuntimeException если невозможно определить сайтовый компонент-обработчик
     * @return string
     */
    public function getPageUrl($isAbsolute = false)
    {
        if (!$this->status instanceof PostStatus) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot detect handler for blog post with guid "{guid}". Status is unknown.',
                    ['guid' => $this->guid]
                )
            );
        }

        switch ($this->status->guid) {
            case PostStatus::GUID_DRAFT : {
                $handler = BlogPostCollection::HANDLER_DRAFT;
                break;
            }
            case PostStatus::GUID_REJECTED: {
                $handler = BlogPostCollection::HANDLER_REJECT;
                break;
            }
            case PostStatus::GUID_NEED_MODERATION : {

                /**
                 * @var UsersModule $usersModule
                 */
                $usersModule = $this->getModuleByClass(UsersModule::className());

                if ($this->author instanceof BlogAuthor && $usersModule->getCurrentUser() === $this->author->user) {
                    $handler = BlogPostCollection::HANDLER_MODERATE_OWN;
                } else {
                    $handler = BlogPostCollection::HANDLER_MODERATE_ALL;
                }
                break;
            }
            default : {
                $handler = ICmsCollection::HANDLER_SITE;
            }
        }

        return $this->getUrlManager()->getSitePageUrl($this, $isAbsolute, $handler);
    }

    /**
     * Изменяет статус публикации.
     * @param string|null $value статус публикации
     * @return $this
     */
    public function setStatus($value)
    {
        $publishStatusProperty = $this->getProperty(self::FIELD_STATUS);
        $publishStatusProperty->setValue($value);

        if ($publishStatusProperty->getIsModified()) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->recalculatePostsCount();
            }
        }

        return $this;
    }

    /**
     * Изменяет автора комментария.
     * @param BlogAuthor|null $value автор
     * @return $this
     */
    public function setAuthor($value)
    {
        if ($this->author instanceof BlogAuthor) {
            $this->author->recalculateCommentsCount();
        }

        $this->getProperty(self::FIELD_AUTHOR)->setValue($value);

        if ($value instanceof BlogAuthor) {
            $value->recalculatePostsCount();
        }

        return $this;
    }

    /**
     * Мутатор для контентного поля.
     * @param string $contents контент поста
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
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'model:blogPost';
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($role, $operationName, array $assertions)
    {
        if (!$role instanceof ComponentRoleProvider || !$role->getIdentity() instanceof RegisteredUser) {
            return false;
        }

        /**
         * @var RegisteredUser $user
         */
        $user = $role->getIdentity();
        $result = true;

        foreach ($assertions as $assertion) {
            switch($assertion) {
                case 'own': {
                    $result = $result && ($this->author->profile === $user);
                    break;
                }
                default: {
                return false;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isInIndex()
    {
        return $this->status && ($this->status->guid === PostStatus::GUID_PUBLISHED) && $this->active && !$this->trashed;
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
            ->where(BlogComment::FIELD_POST)->equals($this)
            ->where(BlogComment::FIELD_STATUS . '.' . CommentStatus::FIELD_GUID)
                ->equals(CommentStatus::GUID_PUBLISHED)
            ->where(BlogComment::FIELD_TRASHED)->equals(false)
            ->getTotal();
    }
}
