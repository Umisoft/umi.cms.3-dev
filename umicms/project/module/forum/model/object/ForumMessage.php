<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\model\object;

/**
 * Класс описывающий сообщение форума.
 *
 * @property ForumAuthor $author автор сообщения
 */
class ForumMessage extends BaseForumMessage
{
    /**
     * Тип объекта
     */
    const TYPE_NAME = 'message';
    /**
     * Имя поля для хранения комментария
     */
    const FIELD_CONTENTS = 'contents';
    /**
     * Имя поля для хранения необработанного контента комментария
     */
    const FIELD_CONTENTS_RAW = 'contents_raw';
    /**
     * Имя поля для хранения ссылки на автора сообщения
     */
    const FIELD_AUTHOR = 'author';

    /**
     * Мутатор для контентного поля.
     * @param string $contents контент сообщения
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
     * Устанавливает автора, который создал тему.
     * @param ForumAuthor|null $value автор
     * @return $this
     */
    public function setAuthor($value)
    {
        if ($this->author instanceof ForumAuthor) {
            $this->author->recalculateMessagesCount();
        }

        $this->getProperty(self::FIELD_AUTHOR)->setValue($value);

        if ($value instanceof ForumAuthor) {
            $value->recalculateMessagesCount();
        }

        return $this;
    }
}
 