<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module;

use umicms\model\ModelCollection;

/**
 * Модуль UMI.CMS. Расширяет API системы.
 */
interface IModule
{
    /**
     * Возвращает имя модуля.
     * @return string
     */
    public function getName();

    /**
     * Возвращает коллекцию моделей модуля.
     * @return ModelCollection
     */
    public function getModels();
}
 