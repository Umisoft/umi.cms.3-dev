<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\survey\model\collection;

use umicms\orm\collection\CmsPageCollection;
use umi\acl\IAclResource;
use umi\acl\IAclAssertionResolver;

/**
 * Коллекция для работы с опросами.
 * @package umicms\project\module\survey\model\collection
 */
class SurveyCollection extends CmsPageCollection implements IAclResource, IAclAssertionResolver
{

}
