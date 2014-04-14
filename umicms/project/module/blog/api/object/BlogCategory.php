<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

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
 