<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

use umicms\project\module\users\api\UsersModule;

/**
 * Комментарий к посту.
 *
 * @property UsersModule $author автор поста
 * @property string $contents комментарий
 * @property string $publishStatus статус публикации комментария
 */
class BlogComment extends BlogBaseComment
{
    /**
     * Тип объекта
     */
    const TYPE = 'comment';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
    /**
     * Имя поля для хранения комментария
     */
    const FIELD_CONTENTS = 'contents';
    /**
     * Имя поля для хранения статуса публикации комментария
     */
    const FIELD_PUBLISH_STATUS = 'publishStatus';
    /**
     * Форма добавления комментария
     */
    const FORM_ADD_COMMENT = 'addComment';
    /**
     * Форма одобрения комментария
     */
    const FORM_CHANGE_COMMENT_STATUS = 'changeStatus';
    /**
     * Статус комментария: опубликован
     */
    const COMMENT_STATUS_PUBLISHED = 'published';
    /**
     * Статус комментария: отклонён
     */
    const COMMENT_STATUS_REJECTED = 'rejected';
    /**
     * Статус комментария: требует модерации
     */
    const COMMENT_STATUS_NEED_MODERATE = 'moderate';

    /**
     * Выставляет статус комментария опубликован.
     * @return $this
     */
    public function published()
    {
        $this->publishStatus = self::COMMENT_STATUS_PUBLISHED;
        return $this;
    }

    /**
     * Выставляет статус поста требует модерации.
     * @return $this
     */
    public function needModerate()
    {
        $this->publishStatus = self::COMMENT_STATUS_NEED_MODERATE;
        return $this;
    }

    /**
     * Выставляет статус комментария отклонён.
     * @return $this
     */
    public function rejected()
    {
        $this->publishStatus = self::COMMENT_STATUS_REJECTED;
        return $this;
    }
}
 