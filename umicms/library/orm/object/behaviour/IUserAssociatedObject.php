<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object\behaviour;

use umicms\orm\object\ICmsObject;
use umicms\project\module\users\model\object\BaseUser;

/**
 * Интерфейс объекта, связанного с пользователем.
 *
 * @property BaseUser $user связанный пользователь
 */
interface IUserAssociatedObject extends ICmsObject
{
    /**
     * Имя поля для хранения связанного пользователя
     */
    const FIELD_USER = 'user';
}
 