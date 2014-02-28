<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umicms\exception\OutOfBoundsException;

/**
 * Интерфейс коллекции, имеющей компоненты-обработчики.
 */
interface IApplicationHandlersAware
{
    /**
     * Возвращает путь к компоненту, обрабатывающему коллекцию.
     * @param string $applicationName имя приложения
     * @throws OutOfBoundsException если обработчик не зарегистрирован
     * @param string
     */
    public function getHandlerPath($applicationName);

    /**
     * Возвращает список компонентов-обработчиков.
     * @return array
     */
    public function getHandlerList();

    /**
     * Проверяет, есть ли обработчик у коллекции для указанного приложения.
     * @param string $applicationName имя приложения
     * @return bool
     */
    public function hasHandler($applicationName);

}
 