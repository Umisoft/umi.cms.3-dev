<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\controller;

use umi\acl\IAclResource;

/**
 * Базовый контроллер UMI.CMS, доступ к которому контролируется через ACL.
 */
abstract class BaseAccessRestrictedController extends BaseController implements IAclResource
{
    const ACL_RESOURCE_PREFIX = 'controller:';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }
}
 