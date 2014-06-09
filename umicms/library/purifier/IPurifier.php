<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\purifier;

/**
 * Интерфейс очистителя контента.
 */
interface IPurifier
{
    /**
     * Применяет очиститель.
     * @param string $string входная строка, подлежащая очистке
     * @param array $options опции для конфигурирования очистителя
     * @return string
     */
    public function purify($string, array $options = []);
}
 