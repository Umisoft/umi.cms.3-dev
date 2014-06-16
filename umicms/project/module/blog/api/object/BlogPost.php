<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\api\object;

use DateTime;
use umi\acl\IAclAssertionResolver;
use umi\acl\IAclResource;
use umi\hmvc\acl\ComponentRoleProvider;
use umi\orm\object\property\calculable\ICounterProperty;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\blog\api\collection\BlogPostCollection;
use umicms\project\module\users\api\object\AuthorizedUser;

/**
 * Пост блога.
 *
 * @property BlogAuthor $author автор поста
 * @property string $announcement анонс
 * @property BlogCategory|null $category категория поста
 * @property IManyToManyObjectSet $tags тэги, к которым относится пост
 * @property DateTime $publishTime дата публикации поста
 * @property string $publishStatus статус публикации поста
 * @property int $commentsCount количество комментариев к посту
 * @property string $oldUrl старый URL поста
 * @property string $source источник поста
 */
class BlogPost extends CmsObject implements ICmsPage, IAclResource, IAclAssertionResolver
{
    use TCmsPage;

    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
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
    const FIELD_PUBLISH_STATUS = 'publishStatus';
    /**
     * Имя поля для хранения количества комментариев к посту
     */
    const FIELD_COMMENTS_COUNT = 'commentsCount';
    /**
     * Имя поля для хранения источника поста
     */
    const FIELD_SOURCE = 'source';
    /**
     * Форма добавления поста
     */
    const FORM_ADD_POST = 'addPost';
    /**
     * Форма редактирования поста
     */
    const FORM_EDIT_POST = 'editPost';
    /**
     * Форма публикации поста
     */
    const FORM_PUBLISH_POST = 'publish';
    /**
     * Форма отправки поста на модерацию
     */
    const FORM_MODERATE_POST = 'moderate';
    /**
     * Форма отклонения поста для публикации
     */
    const FORM_REJECT_POST = 'reject';
    /**
     * Форма помещения поста в черновики
     */
    const FORM_DRAFT_POST = 'draft';
    /**
     * Статус поста: черновик
     */
    const POST_STATUS_DRAFT = 'draft';
    /**
     * Статус поста: опубликован
     */
    const POST_STATUS_PUBLISHED = 'published';
    /**
     * Статус поста: отклонён
     */
    const POST_STATUS_REJECTED = 'rejected';
    /**
     * Статус поста: требует модерации
     */
    const POST_STATUS_NEED_MODERATE = 'moderate';

    /**
     * Возвращает URL поста.
     * @param bool $isAbsolute абсолютный ли URL
     * @return string
     */
    public function getPageUrl($isAbsolute = false)
    {
        switch ($this->publishStatus) {
            case self::POST_STATUS_DRAFT : {
                $handler = BlogPostCollection::HANDLER_DRAFT;
                break;
            }
            case self::POST_STATUS_REJECTED : {
                $handler = BlogPostCollection::HANDLER_REJECT;
                break;
            }
            case self::POST_STATUS_NEED_MODERATE : {
                $handler = BlogPostCollection::HANDLER_MODERATE;
                break;
            }
            default : {
                $handler = ICmsCollection::HANDLER_SITE;
            }
        }

        return $this->getUrlManager()->getSitePageUrl($this, $isAbsolute, $handler);
    }

    /**
     * Выставляет статус поста черновик.
     * @return $this
     */
    public function draft()
    {
        $this->publishStatus = self::POST_STATUS_DRAFT;
        return $this;
    }

    /**
     * Выставляет статус поста опубликован.
     * @return $this
     */
    public function publish()
    {
        $this->publishStatus = self::POST_STATUS_PUBLISHED;
        return $this;
    }

    /**
     * Выставляет статус поста требует модерации.
     * @return $this
     */
    public function needModeration()
    {
        $this->publishStatus = self::POST_STATUS_NEED_MODERATE;
        return $this;
    }

    /**
     * Выставляет статус поста отклонён.
     * @return $this
     */
    public function reject()
    {
        $this->publishStatus = self::POST_STATUS_REJECTED;
        return $this;
    }

    /**
     * Увеличивает количество комментариев, опубликованных к посту.
     * @return $this
     */
    public function incrementCommentCount()
    {
        /** @var ICounterProperty $postCommentsCount */
        $postCommentsCount = $this->getProperty(self::FIELD_COMMENTS_COUNT);
        $postCommentsCount->increment();

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
        if (!$role instanceof ComponentRoleProvider || !$role->getIdentity() instanceof AuthorizedUser) {
            return false;
        }

        /**
         * @var AuthorizedUser $user
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

}
 