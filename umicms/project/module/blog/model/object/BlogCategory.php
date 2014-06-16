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

use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Категория поста.
 *
 * @property IObjectSet $posts категория поста
 */
class BlogCategory extends CmsHierarchicObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения постов
     */
    const FIELD_POSTS = 'posts';
}
 