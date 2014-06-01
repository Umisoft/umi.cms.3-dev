<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\slugGenerator;

/**
 * Интерфейс генератора slug'ов.
 */
interface ISlugGenerator
{
    /**
     * Генерирует slug.
     * @param string $text строка для генерации slug
     * @param array $options опции для генерации slug
     * @return string
     */
    public function generate($text, array $options = []);
}
 