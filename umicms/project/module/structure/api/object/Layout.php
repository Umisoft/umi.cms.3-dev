<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\IRecyclableObject;

/**
 * Шаблон.
 *
 * @property string $fileName имя файла шаблона
 */
class Layout extends CmsObject implements IRecyclableObject
{
    /**
     * Имя поля для хранения имени файла шаблона
     */
    const FIELD_FILE_NAME = 'fileName';
}
