<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\controller;

use umi\acl\IAclResource;

/**
 * Базовый контроллер UMI.CMS, доступ к которому контролируется через ACL.
 */
abstract class BaseSecureController extends BaseController implements IAclResource
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
 