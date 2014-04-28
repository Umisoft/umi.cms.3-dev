<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

use DateTime;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\blog\api\collection\BlogPostCollection;

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
class BlogPost extends CmsObject implements ICmsPage
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
     * Форма помещения и извлечения поста в черновики
     */
    const FORM_CHANGE_POST_STATUS = 'changeStatusPost';
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
    public function published()
    {
        $this->publishStatus = self::POST_STATUS_PUBLISHED;
        return $this;
    }

    /**
     * Выставляет статус поста требует модерации.
     * @return $this
     */
    public function needModerate()
    {
        $this->publishStatus = self::POST_STATUS_NEED_MODERATE;
        return $this;
    }

    /**
     * Выставляет статус поста отклонён.
     * @return $this
     */
    public function rejected()
    {
        $this->publishStatus = self::POST_STATUS_REJECTED;
        return $this;
    }
}
 