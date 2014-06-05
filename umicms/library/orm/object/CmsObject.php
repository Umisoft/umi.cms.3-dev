<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
