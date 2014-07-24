<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Выпуски рассылок.
 *
 */
class Release extends CmsObject
{
    /**
     *  Имя поля для хранения рассылок
     */
    const FIELD_DISPATCHES = 'dispatches';


}
