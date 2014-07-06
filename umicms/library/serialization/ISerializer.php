<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization;

/**
 * Интерфейс сериализатора объектов.
 */
interface ISerializer
{
    /**
     * Устанавливает опции сериализации
     * @param array $options
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Устанавливает список имен исключений.
     * @param array $excludes
     * @return self
     */
    public function setExcludes(array $excludes);

    /**
     * Инициализирует сериализатор.
     * @return self
     */
    public function init();

    /**
     * Возвращает результат сериализации.
     * @return string
     */
    public function output();
}
