<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugify;

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
 