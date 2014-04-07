<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\object;

use umi\hmvc\component\IComponent;

/**
 * Супервайзер.
 */
class Supervisor extends AuthorizedUser
{
    /**
     * {@inheritdoc}
     */
    public function isAllowed(IComponent $component, $roleName, $resourceName)
    {
        return true;
    }
}
 