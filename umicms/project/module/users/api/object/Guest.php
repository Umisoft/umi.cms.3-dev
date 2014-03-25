<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\object;

use umi\orm\objectset\IManyToManyObjectSet;

/**
 * Незарегистрированный пользователь.
 *
 * @property IManyToManyObjectSet $groups группы, в которые входит пользователь
 */
class Guest extends BaseUser
{

}
 