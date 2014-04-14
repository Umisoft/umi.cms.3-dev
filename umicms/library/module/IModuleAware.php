<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
 