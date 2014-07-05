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
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Базовый класс комментария к посту.
 *
 * @property BlogPost $post пост, к которому относится комментарий
 * @property DateTime $publishTime дата и время публикации комментария
 */
abstract class BlogBaseComment extends CmsHierarchicObject implements IRecyclableObject
{
    /**
     * Имя поля для хранения поста, к которому относится комментарий
     */
    const FIELD_POST = 'post';
    /**
     * Имя поля для хранения даты и времени публикации комментария
     */
    const FIELD_PUBLISH_TIME = 'publishTime';
}
 