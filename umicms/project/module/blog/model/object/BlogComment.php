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

/**
 * Комментарий к посту.
 *
 * @property BlogAuthor $author автор поста
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
     * Имя поля для хранения необработанного контента комментария
     */
    const FIELD_CONTENTS_RAW = 'contents_raw';
    /**
     * Имя поля для хранения статуса публикации комментария
     */
    const FIELD_PUBLISH_STATUS = 'publishStatus';
    /**
     * Форма добавления комментария
     */
    const FORM_ADD_COMMENT = 'addComment';
    /**
     * Форма публикации комментария
     */
    const FORM_PUBLISH_COMMENT = 'publish';
    /**
     * Форма отклонения комментария
     */
    const FORM_REJECT_COMMENT = 'reject';
    /**
     * Форма снятия с публикации
     */
    const FORM_UNPUBLISH_COMMENT = 'unpublish';
    /**
     * Статус комментария: опубликован
     */
    const COMMENT_STATUS_PUBLISHED = 'published';
    /**
     * Статус комментария: отклонен
     */
    const COMMENT_STATUS_REJECTED = 'rejected';
    /**
     * Статус комментария: снят с публикации
     */
    const COMMENT_STATUS_UNPUBLISHED = 'unpublished';
    /**
     * Статус комментария: требует модерации
     */
    const COMMENT_STATUS_NEED_MODERATE = 'moderate';

    /**
     * Мутатор для контентного поля.
     * @param string $contents контент комментария
     * @param string $locale локаль
     * @return $this
     */
    public function setContents($contents, $locale)
    {
        $this->getProperty(self::FIELD_CONTENTS, $locale)
            ->setValue($contents);

        $this->getProperty(self::FIELD_CONTENTS_RAW, $locale)
            ->setValue($contents);

        return $this;
    }

    /**
     * Публикует комментарий.
     * @return $this
     */
    public function publish()
    {
        if ($this->publishStatus !== self::COMMENT_STATUS_PUBLISHED) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->incrementCommentCount();
            }
            $this->post->incrementCommentCount();

            $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::COMMENT_STATUS_PUBLISHED);
        }

        return $this;
    }

    /**
     * Выставляет статус комментария: требует модерации.
     * @return $this
     */
    public function needModerate()
    {
        if ($this->publishStatus === self::COMMENT_STATUS_PUBLISHED) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->decrementCommentCount();
            }
            $this->post->decrementCommentCount();
        }

        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::COMMENT_STATUS_NEED_MODERATE);
        return $this;
    }

    /**
     * Выставляет статус комментария: отклонен.
     * @return $this
     */
    public function reject()
    {
        if ($this->publishStatus === self::COMMENT_STATUS_PUBLISHED) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->decrementCommentCount();
            }
            $this->post->decrementCommentCount();
        }

        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::COMMENT_STATUS_REJECTED);
        return $this;
    }

    /**
     * Выставляет статус комментария: снят с публикации.
     * @return $this
     */
    public function unPublish()
    {
        if ($this->publishStatus === self::COMMENT_STATUS_PUBLISHED) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->decrementCommentCount();
            }
            $this->post->decrementCommentCount();
        }

        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::COMMENT_STATUS_UNPUBLISHED);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'model:blogComment';
    }

    /**
     * Мутатор для поля статус публикации.
     * @param string $value статус публикации
     * @return $this
     */
    public function changeStatus($value)
    {
        switch($value) {
            case self::COMMENT_STATUS_PUBLISHED : {
                $this->publish();
                break;
            }
            case self::COMMENT_STATUS_NEED_MODERATE : {
                $this->needModerate();
                break;
            }
            case self::COMMENT_STATUS_REJECTED : {
                $this->reject();
                break;
            }
            case self::COMMENT_STATUS_UNPUBLISHED : {
                $this->unPublish();
                break;
            }
        }

        return $this;
    }
}
 