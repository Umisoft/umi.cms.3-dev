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
 