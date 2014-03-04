<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\callstack;

use SplStack;

/**
 * Интерфейс для внедрения стека вызова страниц сайта.
 */
interface IPageCallStackAware
{
    /**
     * Устанавливает стек вызова страниц.
     * @param SplStack $callStack
     */
    public function setPageCallStack(SplStack $callStack);
}
 