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
 * Комментарий к посту от зарегистрированного пользователя.
 *
 * @property BlogAuthor $author автор комментария
 * @property string $contents комментарий
 * @property CommentStatus $status статус публикации комментария
 */
class BlogComment extends BaseBlogComment
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
    const FIELD_STATUS = 'status';
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
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'model:blogComment';
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
                $this->author->recalculateCommentsCount();
            }
            if ($this->post instanceof BaseBlogPost) {
                $this->post->recalculateCommentsCount();
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
            $value->recalculateCommentsCount();
        }

        return $this;
    }
}
 