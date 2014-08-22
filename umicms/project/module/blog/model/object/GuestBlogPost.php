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

use umicms\exception\RuntimeException;

/**
 * Пост блога от гостя.
 *
 * @property string $authorGuest имя отображения автора поста
 */
class GuestBlogPost extends BaseBlogPost
{
    /**
     * Тип поста блога
     */
    const TYPE = 'guestBlogPost';
    /**
     * Имя поля для хранения имени отображения автора поста
     */
    const FIELD_AUTHOR = 'authorGuest';

    /**
     * Изменяет статус публикации.
     * @param string|null $value статус публикации
     * @return $this
     */
    public function setStatus($value)
    {
        $publishStatusProperty = $this->getProperty(self::FIELD_STATUS);
        $publishStatusProperty->setValue($value);

        return $this;
    }

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

        if (!$this->author instanceof BlogAuthor) {
            return $this->getUrlManager()->getSitePageUrl($this->category);
        }
    }
}
