<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\module;

use umicms\module\toolbox\ModuleTools;

/**
 * Интерфейс для внедрения инструментария для работы с модулями
 */
interface IModuleAware
{
    /**
     * Устанавливает инструментарий для работы с модулями.
     * @param ModuleTools $moduleTools
     */
    public function setModuleTools(ModuleTools $moduleTools);
}
 