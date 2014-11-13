<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\collection;

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\project\module\users\model\object\UserAuthCookie;

/**
 * @method UserAuthCookie add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает auth-куку.
 */
class UserAuthCookieCollection extends CmsCollection
{

} 