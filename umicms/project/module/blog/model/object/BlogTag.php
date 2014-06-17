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

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Тэги блога.
 *
 * @property IManyToManyObjectSet $posts список постов, относящихся к тэгу
 * @property IManyToManyObjectSet $rss RSS-ленты тэга
 * @property int $postsCount количество постов, относящихся к тэгу
 */
class BlogTag extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения списка постов, которые относятся к тэгу
     */
    const FIELD_POSTS = 'posts';
    /**
     * Имя поля для хранения RSS-ленты
     */
    const FIELD_RSS = 'rss';
    /**
     * Имя поля для хранения количества постов, которые относятся к тэгу
     */
    const FIELD_POSTS_COUNT = 'postsCount';
}
 