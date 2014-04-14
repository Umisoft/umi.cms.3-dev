<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Тэги.
 *
 * @property IManyToManyObjectSet $posts список постов, относящихся к тэгу
 * @property IManyToManyObjectSet $rss RSS-ленты тэга
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
}
 