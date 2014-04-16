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
use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\users\api\UsersModule;

/**
 * Автор поста.
 *
 * @property UsersModule $profile профиль автора
 * @property int $postsCount количество постов автора
 * @property int $commentsCount количество постов автора
 * @property DateTime $lastActivity дата последней активности
 * @property IObjectSet $posts посты автора
 */
class BlogAuthor extends CmsObject implements ICmsPage
{
    use TCmsPage;

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
}
 