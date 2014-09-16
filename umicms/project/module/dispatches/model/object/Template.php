<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use umicms\orm\object\CmsObject;

/**
 * Шаблон письма выпуска рассылки.
 *
 * @property string $fileName имя файла шаблона
 */
class Template extends CmsObject
{
    /**
     * Имя поля для пути к файлу шаблона
     */
    const FIELD_FILE_NAME = 'fileName';

}
