<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\object;

use umi\orm\object\Object;
use umicms\hmvc\url\IUrlManagerAware;

/**
 * Класс простого объекта UMI.CMS.
 */
class CmsObject extends Object implements ICmsObject, IUrlManagerAware
{
    use TCmsObject;
}
