<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Базовый класс комментария к посту.
 *
 * @property BlogPost $post пост, к которому относится комментарий
 */
abstract class BlogBaseComment extends CmsHierarchicObject implements IRecyclableObject, IActiveAccessibleObject
{
    /**
     * Имя поля для хранения поста, к которому относится комментарий
     */
    const FIELD_POST = 'post';
}
 