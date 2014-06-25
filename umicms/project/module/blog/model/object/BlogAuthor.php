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
use umi\orm\object\property\calculable\ICounterProperty;
use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\users\model\object\AuthorizedUser;

/**
 * Автор поста.
 *
 * @property AuthorizedUser $profile профиль автора
 * @property int $postsCount количество постов автора
 * @property int $commentsCount количество постов автора
 * @property DateTime $lastActivity дата последней активности
 * @property IObjectSet $posts посты автора
 */
class BlogAuthor extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения необработанного контента
     */
    const FIELD_PAGE_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения профиля автора
     */
    const FIELD_PROFILE = 'profile';
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
     * Увеличивает количество постов, опубликованных автором.
     * @return $this
     */
    public function incrementPostCount()
    {
        /** @var ICounterProperty $authorPostCount */
        $authorPostCount = $this->getProperty(self::FIELD_POSTS_COUNT);
        $authorPostCount->increment();

        return $this;
    }

    /**
     * Уменьшает количество постов, опубликованных автором.
     * @return $this
     */
    public function decrementPostCount()
    {
        /** @var ICounterProperty $authorPostCount */
        $authorPostCount = $this->getProperty(self::FIELD_POSTS_COUNT);
        $authorPostCount->decrement();

        return $this;
    }

    /**
     * Увеличивает количество комментариев, опубликованных автором.
     * @return $this
     */
    public function incrementCommentCount()
    {
        /** @var ICounterProperty $authorCommentsCount */
        $authorCommentsCount = $this->getProperty(BlogAuthor::FIELD_COMMENTS_COUNT);
        $authorCommentsCount->increment();

        return $this;
    }
}
 