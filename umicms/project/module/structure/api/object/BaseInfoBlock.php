<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\api\object;

use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\CmsObject;

/**
 * Информационный блок сайта.
 */
abstract class BaseInfoBlock extends CmsObject implements ILockedAccessibleObject
{
    /**
     * Имя поля для хранения названия информационного блока
     */
    const FIELD_INFOBLOCK_NAME = 'infoblockName';
}
 