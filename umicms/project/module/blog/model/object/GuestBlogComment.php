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
 * Комментарий к посту от гостя.
 *
 * @property string $authorGuest автор комментария
 * @property string $contents комментарий
 * @property CommentStatus $status статус публикации комментария
 */
class GuestBlogComment extends BlogComment
{
    /**
     * Тип объекта
     */
    const TYPE = 'guestComment';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'authorGuest';

    /**
     * Изменяет автора комментария.
     * @param string|null $value автор
     * @return $this
     */
    public function setAuthor($value)
    {
        $this->getProperty(self::FIELD_AUTHOR)->setValue($value);

        return $this;
    }
}
 