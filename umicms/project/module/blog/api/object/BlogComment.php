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
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\project\module\users\api\UsersModule;

/**
 * Комментарий к посту.
 *
 * @property UsersModule $author автор поста
 * @property BlogPost $post пост, к которому относится комментарий
 * @property string $contents комментарий
 * @property DateTime $publishTime дата и время публикации комментария
 */
class BlogComment extends CmsHierarchicObject implements IRecyclableObject, IActiveAccessibleObject
{
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
    /**
     * Имя поля для хранения поста, к которому относится комментарий
     */
    const FIELD_POST = 'post';
    /**
     * Имя поля для хранения комментария
     */
    const FIELD_CONTENTS = 'contents';
    /**
     * Имя поля для хранения даты и времени публикации комментария
     */
    const FIELD_PUBLISH_TIME = 'publishTime';
}
 