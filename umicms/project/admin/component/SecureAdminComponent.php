<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\component;

use umi\acl\IAclResource;

/**
 * Компонент административной панели, доступ к которому контролируется через ACL.
 */
class SecureAdminComponent extends AdminComponent implements IAclResource
{
    /**
     * Префикс имени ACL-ресурса
     */
    const ACL_RESOURCE_PREFIX = 'component:';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }
}
 